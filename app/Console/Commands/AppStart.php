<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AppStart extends Command
{
    protected $signature = 'app:start {--email= : Email del admin} {--name=Admin : Nombre si el usuario se crea}';

    protected $description = 'Carga los planes base y crea o promueve al usuario admin';

    public function handle(): int
    {
        $this->info('1/2 Planes...');
        $this->call('db:seed', ['--class' => 'PlanSeeder', '--force' => true]);

        $this->info('2/2 Admin...');
        $email = $this->option('email') ?: $this->ask('Email del admin');

        if (Validator::make(['email' => $email], ['email' => 'required|email'])->fails()) {
            $this->error('Email invalido.');
            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->forceFill(['is_admin' => true])->save();
            $this->info("Usuario existente promovido a admin: {$email}");
        } else {
            $password = (string) $this->secret('Contrasena (minimo 12 caracteres)');
            if (mb_strlen($password) < 12) {
                $this->error('La contrasena debe tener al menos 12 caracteres.');
                return self::FAILURE;
            }

            $user = new User();
            $user->forceFill([
                'name' => $this->option('name'),
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_admin' => true,
            ])->save();
            $this->info("Admin creado: {$email}");
        }

        $this->table(['Email', 'Admin', 'ID'], [[$user->email, 'si', $user->id]]);

        return self::SUCCESS;
    }
}
