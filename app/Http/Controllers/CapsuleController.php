<?php
namespace App\Http\Controllers;

use App\Models\Capsule;
use Illuminate\Http\Request;

class CapsuleController extends Controller
{
    public function index() {
        return view('dashboard', ['capsules' => auth()->user()->capsules()->latest()->get()]);
    }
    public function create() { return view('capsules.create'); }
    public function store(Request $r) {
        $data = $r->validate(['title' => 'required|max:120', 'body' => 'required']);
        $capsule = $r->user()->capsules()->create($data + ['status' => 'draft']);
        return redirect()->route('capsules.show', $capsule);
    }
    public function show(Capsule $capsule) {
        $this->authorize('view', $capsule);
        return view('capsules.show', compact('capsule'));
    }
    public function destroy(Capsule $capsule) {
        $this->authorize('delete', $capsule);
        $capsule->delete();
        return redirect()->route('dashboard');
    }
}
