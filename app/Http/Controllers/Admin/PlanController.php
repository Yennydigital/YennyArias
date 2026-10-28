<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return view('admin.plans.index', ['plans' => Plan::orderBy('position')->get()]);
    }

    public function show(Plan $plan)
    {
        $activeSubs = \App\Models\Subscription::where('plan_id', $plan->id)
            ->where('status', 'active')
            ->where('current_period_end', '>', now())
            ->count();

        return view('admin.plans.show', ['plan' => $plan, 'activeSubs' => $activeSubs]);
    }

    public function create()
    {
        return view('admin.plans.form', ['plan' => new Plan(['currency' => 'USD', 'is_active' => true]), 'isNew' => true]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules() + [
            'code' => 'required|string|max:40|alpha_dash|unique:plans,code',
        ]);

        Plan::create($this->normalize($request, $data));

        return redirect()->route('admin.plans.index')->with('ok', 'Plan creado.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.form', ['plan' => $plan, 'isNew' => false]);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate($this->rules());

        // El plan free es el valor por defecto del sitio: no se puede desactivar.
        $payload = $this->normalize($request, $data);
        if ($plan->code === 'free') {
            $payload['is_active'] = true;
        }

        $plan->update($payload);

        return redirect()->route('admin.plans.index')->with('ok', 'Plan actualizado.');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:60',
            'currency' => 'required|in:USD',
            'price_monthly' => 'required|numeric|min:0|max:99999999',
            'price_yearly' => 'nullable|numeric|min:0|max:99999999',
            'max_photos' => 'required|integer|min:0|max:20',
            'max_days' => 'required|integer|min:1|max:365',
            'max_letters' => 'nullable|integer|min:1|max:100000',
            'position' => 'required|integer|min:0|max:100',
        ];
    }

    private function normalize(Request $request, array $data): array
    {
        $data['price_yearly'] = $request->filled('price_yearly') ? $data['price_yearly'] : null;
        $data['allow_music'] = $request->boolean('allow_music');
        $data['premium_templates'] = $request->boolean('premium_templates');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
