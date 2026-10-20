<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Support\Plans;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $sub = Subscription::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('current_period_end', '>', now())
            ->orderByDesc('current_period_end')
            ->first();

        return view('plans.index', [
            'plans' => Plan::where('is_active', true)->orderBy('position')->get(),
            'current' => Plans::for($user),
            'sub' => $sub,
        ]);
    }
}
