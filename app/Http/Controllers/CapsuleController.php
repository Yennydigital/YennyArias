<?php

namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Support\CapsuleVault;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CapsuleController extends Controller
{
    public function create(Request $request)
    {
        $request->session()->forget('capsule_done');

        return view('capsules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:3000'],
            'from' => ['nullable', 'string', 'max:60'],
            'to' => ['nullable', 'string', 'max:60'],
            'opens_at' => ['nullable', 'date'],
            'keep' => ['nullable', 'in:7,30,90,365'],
            'tz' => ['nullable', 'string', 'max:64'],
        ]);

        $tz = in_array($data['tz'] ?? '', timezone_identifiers_list(), true)
            ? $data['tz']
            : 'America/Argentina/Buenos_Aires';

        $opensAt = null;
        if (! empty($data['opens_at'])) {
            $parsed = Carbon::parse($data['opens_at'], $tz)->setTimezone(config('app.timezone'));
            if ($parsed->gt(now()->addMinutes(2))) {
                $opensAt = $parsed;
            }
        }

        $expiresAt = ($opensAt ?? now())->copy()->addDays((int) ($data['keep'] ?? 30));
        $burn = $request->boolean('burn');

        $code = CapsuleVault::newCode();
        [$salt, $blob] = CapsuleVault::seal([
            'message' => $data['message'],
            'from' => $data['from'] ?? null,
            'to' => $data['to'] ?? null,
        ], $code);

        Capsule::create([
            'code_hash' => CapsuleVault::lookup($code),
            'salt' => $salt,
            'ciphertext' => $blob,
            'opens_at' => $opensAt,
            'expires_at' => $expiresAt,
            'burn_after_read' => $burn,
        ]);

        $request->session()->put('capsule_done', [
            'code' => $code,
            'opens_at' => $opensAt ? $opensAt->toIso8601String() : null,
            'expires_at' => $expiresAt->toIso8601String(),
            'burn' => $burn,
        ]);

        return redirect()->route('capsulas.listo');
    }

    public function done(Request $request)
    {
        $done = $request->session()->get('capsule_done');
        if (! $done) {
            return redirect()->route('capsulas.crear');
        }

        return view('capsules.done', ['done' => $done]);
    }

    public function openForm()
    {
        return view('capsules.open');
    }

    public function open(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'max:40']]);
        $code = CapsuleVault::normalize($request->input('code'));

        if (! CapsuleVault::isValidFormat($code)) {
            return back()->withErrors(['code' => 'Ese código no es válido. Son 12 caracteres.'])->withInput();
        }

        $capsule = Capsule::where('code_hash', CapsuleVault::lookup($code))->first();
        if (! $capsule) {
            return back()->withErrors(['code' => 'No encontramos ninguna cápsula con ese código.'])->withInput();
        }

        if ($capsule->expires_at->isPast()) {
            $capsule->delete();

            return back()->withErrors(['code' => 'Esta cápsula ya caducó.'])->withInput();
        }

        if ($capsule->opens_at && $capsule->opens_at->isFuture()) {
            return view('capsules.waiting', [
                'opensAt' => $capsule->opens_at->toIso8601String(),
                'code' => $code,
            ]);
        }

        $payload = CapsuleVault::open($capsule->salt, $capsule->ciphertext, $code);
        if (! $payload) {
            return back()->withErrors(['code' => 'No pudimos abrir esta cápsula.'])->withInput();
        }

        $burn = $capsule->burn_after_read;
        $expires = $capsule->expires_at->toIso8601String();

        if ($burn) {
            $capsule->delete();
        } elseif (! $capsule->opened_at) {
            $capsule->update(['opened_at' => now()]);
        }

        return view('capsules.show', [
            'payload' => $payload,
            'burn' => $burn,
            'expires' => $expires,
        ]);
    }
}
