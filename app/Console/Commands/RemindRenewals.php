<?php

namespace App\Console\Commands;

use App\Mail\RenewalReminderMail;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RemindRenewals extends Command
{
    protected $signature = 'subscriptions:remind {--dry : Solo lista, no envia}';

    protected $description = 'Avisa por mail a quienes su plan esta por vencer';

    public function handle(): int
    {
        $subs = Subscription::with('plan')
            ->where('status', 'active')
            ->where('current_period_end', '>', now())
            ->where('current_period_end', '<=', now()->addDays(15))
            ->get();

        $n = 0;
        foreach ($subs as $sub) {
            if (! $sub->plan || (float) $sub->plan->price_monthly <= 0) {
                continue;
            }
            $days = $sub->billing_cycle === 'yearly' ? 15 : 5;
            if ($sub->current_period_end->gt(now()->addDays($days))) {
                continue;
            }
            if ((string) $sub->renewal_notice_for === $sub->current_period_end->format('Y-m-d H:i:s')) {
                continue;
            }
            $user = User::find($sub->user_id);
            if (! $user || ! $user->email) {
                continue;
            }

            $this->line("{$user->email} | {$sub->plan->name} | vence {$sub->current_period_end}");
            if ($this->option('dry')) {
                continue;
            }

            try {
                Mail::to($user->email)->send(new RenewalReminderMail($user, $sub));
                Subscription::whereKey($sub->id)->update([
                    'renewal_notice_for' => $sub->current_period_end->format('Y-m-d H:i:s'),
                ]);
                $n++;
            } catch (\Throwable $e) {
                Log::error('[Renewal] ' . $e->getMessage());
            }
        }

        $this->info($this->option('dry') ? 'Dry run, no se envio nada.' : "Avisos enviados: $n");

        return self::SUCCESS;
    }
}
