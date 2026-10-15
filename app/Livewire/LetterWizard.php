<?php

namespace App\Livewire;

use App\Support\CapsuleFactory;
use Livewire\Component;

class LetterWizard extends Component
{
    public int $step = 1;

    public string $template = 'love-letter';
    public string $title = '';
    public string $recipient = '';
    public string $sender = '';
    public string $love = '';
    public string $story = '';
    public string $dreams = '';

    public string $opens_at = '';
    public string $keep = '30';
    public bool $burn = false;
    public string $tz = 'America/Argentina/Buenos_Aires';

    public array $templates = [
        'love-letter' => 'Love letter',
        'birthday' => 'Birthday',
        'thank-you' => 'Thank you',
    ];

    protected function composeMessage(): string
    {
        return collect([$this->love, $this->story, $this->dreams])
            ->map(fn ($t) => trim($t))
            ->filter()
            ->implode("\n\n");
    }

    protected function validateContent(): bool
    {
        $this->validate([
            'title' => 'required|string|max:80',
            'recipient' => 'nullable|string|max:60',
            'sender' => 'nullable|string|max:60',
            'love' => 'nullable|string|max:3000',
            'story' => 'nullable|string|max:3000',
            'dreams' => 'nullable|string|max:3000',
        ]);

        $message = $this->composeMessage();

        if ($message === '') {
            $this->addError('love', 'Write at least one section.');
            return false;
        }

        if (mb_strlen($message) > 3000) {
            $this->addError('love', 'Your letter is too long (max 3000 characters in total).');
            return false;
        }

        return true;
    }

    public function pickTemplate(string $key): void
    {
        if (array_key_exists($key, $this->templates)) {
            $this->template = $key;
        }
    }

    public function next(): void
    {
        if ($this->step === 2 && ! $this->validateContent()) {
            return;
        }

        $this->step = min(3, $this->step + 1);
    }

    public function back(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function seal()
    {
        if (! $this->validateContent()) {
            $this->step = 2;
            return;
        }

        $this->validate([
            'opens_at' => 'nullable|date',
            'keep' => 'required|in:7,30,90,365',
            'tz' => 'nullable|string|max:64',
        ]);

        $result = CapsuleFactory::make(
            [
                'message' => $this->composeMessage(),
                'from' => $this->sender !== '' ? $this->sender : null,
                'to' => $this->recipient !== '' ? $this->recipient : null,
                'title' => $this->title,
                'template' => $this->template,
                'sections' => [
                    'love' => $this->love,
                    'story' => $this->story,
                    'dreams' => $this->dreams,
                ],
            ],
            $this->opens_at ?: null,
            $this->tz,
            $this->keep,
            $this->burn,
        );

        // El contenido queda solo cifrado en la cápsula; aquí solo metadatos.
        auth()->user()->letters()->create([
            'template' => $this->template,
            'title' => $this->title,
            'capsule_id' => $result['capsule']->id,
            'sealed_at' => now(),
        ]);

        session()->put('capsule_done', [
            'code' => $result['code'],
            'opens_at' => $result['opens_at'] ? $result['opens_at']->toIso8601String() : null,
            'expires_at' => $result['expires_at']->toIso8601String(),
            'burn' => $this->burn,
        ]);

        return redirect()->route('capsulas.listo');
    }

    public function render()
    {
        return view('livewire.letters.wizard');
    }
}
