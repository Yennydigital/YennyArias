<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;

class VisitsController extends Controller
{
    public function index()
    {
        $daily = PageView::selectRaw('DATE(created_at) as d, COUNT(*) as views, COUNT(DISTINCT ip_hash) as visitors')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('d')->orderBy('d')->get();

        return view('admin.visits', [
            'total' => PageView::count(),
            'today' => PageView::where('created_at', '>=', now()->startOfDay())->count(),
            'last7' => PageView::where('created_at', '>=', now()->subDays(7))->count(),
            'uniques30' => PageView::where('created_at', '>=', now()->subDays(30))->distinct('ip_hash')->count('ip_hash'),
            'daily' => $daily,
            'topPages' => PageView::selectRaw('path, COUNT(*) as views')->groupBy('path')->orderByDesc('views')->limit(10)->get(),
            'topReferers' => PageView::selectRaw('referer, COUNT(*) as views')->whereNotNull('referer')->groupBy('referer')->orderByDesc('views')->limit(10)->get(),
        ]);
    }
}
