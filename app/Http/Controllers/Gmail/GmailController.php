<?php

namespace App\Http\Controllers\Gmail;

use App\Http\Controllers\Controller;
use App\Models\Gmail\GmailConfig;
use App\Services\GmailMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GmailController extends Controller
{
    public function connect()
    {
        abort_unless(app()->environment('local'), 404);
        $state = Str::random(40);
        session(['gmail_oauth_state' => $state]);

        return redirect()->away((new GmailMailService)->authUrl($state));
    }

    public function callback(Request $request)
    {
        abort_unless(app()->environment('local'), 404);

        if ($request->has('error')) {
            return response('Google devolvió un error: ' . e($request->get('error')), 400);
        }
        if (! hash_equals((string) session()->pull('gmail_oauth_state'), (string) $request->get('state'))) {
            return response('Estado OAuth inválido. Volvé a abrir /gmail/connect.', 400);
        }

        try {
            (new GmailMailService)->exchangeCode($request->get('code'));
        } catch (\Throwable $e) {
            return response($e->getMessage(), 500);
        }

        $c = GmailConfig::find(1);
        return response('Gmail conectado: ' . ($c->email ?: 'ok') . '. Ya podés cerrar esta pestaña y probar el formulario de contacto.');
    }
}
