<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $subs = Subscription::with(['plan', 'user'])
            ->when($q !== '', fn ($query) => $query->whereHas('user', fn ($u) => $u->where('email', 'like', '%' . $q . '%')))
            ->latest()
            ->limit(100)
            ->get();

        return view('admin.subscriptions.index', [
            'subs' => $subs,
            'plans' => Plan::where('code', '!=', 'free')->orderBy('position')->get(),
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'days' => 'required|integer|min:1|max:730',
        ]);

        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            return back()->withInput()->withErrors(['email' => 'No existe un usuario con ese email.']);
        }

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $data['plan_id'],
            'status' => 'active',
            'billing_cycle' => $data['billing_cycle'],
            'current_period_start' => now(),
            'current_period_end' => now()->addDays((int) $data['days']),
        ]);

        return redirect()->route('admin.subscriptions.index')->with('ok', 'Suscripcion creada.');
    }

    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', [
            'sub' => $subscription->load(['plan', 'user']),
            'plans' => Plan::where('code', '!=', 'free')->orderBy('position')->get(),
        ]);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:active,cancelled,expired',
            'current_period_start' => 'nullable|date',
            'current_period_end' => 'required|date|after:current_period_start',
        ]);

        $data['cancelled_at'] = $data['status'] === 'cancelled'
            ? ($subscription->cancelled_at ?? now())
            : null;

        $subscription->update($data);

        return redirect()->route('admin.subscriptions.index')->with('ok', 'Suscripcion actualizada.');
    }

    public function extend(Request $request, Subscription $subscription)
    {
        $days = (int) $request->validate(['days' => 'required|integer|min:1|max:730'])['days'];

        $base = $subscription->current_period_end && $subscription->current_period_end->isFuture()
            ? $subscription->current_period_end
            : now();

        $subscription->update([
            'status' => 'active',
            'cancelled_at' => null,
            'current_period_end' => $base->copy()->addDays($days),
        ]);

        return back()->with('ok', 'Suscripcion extendida.');
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return back()->with('ok', 'Suscripcion cancelada.');
    }
}
