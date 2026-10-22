<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        return view('site.index', [
            'plans' => \App\Models\Plan::where('is_active', true)->orderBy('position')->get(),
        ]);
    }
}
