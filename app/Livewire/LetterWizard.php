<?php

namespace App\Livewire;

use App\Support\CapsuleFactory;
use App\Support\MusicLink;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class LetterWizard extends Component
{
    use WithFileUploads;

    private const MAX_PHOTOS = 5;

    public int $step = 1;

    public string $template = 'love-letter';
    public string $title = '';
    public string $recipient = '';
    public string $sender = '';
    public string $love = '';
    public string $story = '';
    public string $dreams = '';

    public $newPhotos = [];
    public array $photos = [];
    public string $music = '';

    public string $opens_at = '';
    public string $keep = '30';
    public bool $burn = false;
    public string $tz = 'America/Argentina/Buenos_Aires';

    public array $templates = [
        'love-letter' => 'Love letter',
        'birthday' => 'Birthday',
        'thank-you' => 'Thank you',
        'starry-night' => 'Starry night',
        'pixel-city' => 'Pixel city',
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
            'music' => 'nullable|string|max:500',
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

        if (trim($this->music) !== '' && MusicLink::clean($this->music) === null) {
            $this->addError('music', 'Use an https link from YouTube, Spotify, Apple Music, Deezer or Amazon Music.');
            return false;
        }

        if (trim($this->music) !== '' && ! $this->plan()->allow_music) {
            $this->addError('music', 'Soundtracks are included in the Plus plan.');
            return false;
        }

        return true;
    }

    public function getCurrentPlanProperty(): \App\Models\Plan
    {
        return $this->plan();
    }

    protected function plan(): \App\Models\Plan
    {
        return \App\Support\Plans::for(auth()->user());
    }

    public function updatedNewPhotos(): void
    {
        $this->resetErrorBag('newPhotos');
        $incoming = Arr::wrap($this->newPhotos);

        $validator = Validator::make(
            ['newPhotos' => $incoming],
            ['newPhotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192']],
            [
                'newPhotos.*.image' => 'Only images are allowed.',
                'newPhotos.*.mimes' => 'Photos must be JPG, PNG or WebP.',
                'newPhotos.*.max' => 'Each photo must be 8 MB or less.',
            ]
        );

        if ($validator->fails()) {
            $this->addError('newPhotos', $validator->errors()->first());
            $this->newPhotos = [];
            return;
        }

        foreach ($incoming as $file) {
            if (count($this->photos) >= $this->plan()->max_photos) {
                $this->addError('newPhotos', 'You can add up to ' . $this->plan()->max_photos . ' photos.');
                break;
            }
            $this->photos[] = $file;
        }

        $this->newPhotos = [];
    }

    public function removePhoto(int $index): void
    {
        if (isset($this->photos[$index])) {
            try {
                $this->photos[$index]->delete();
            } catch (\Throwable $e) {
                // el archivo temporal se limpia solo en 24 h
            }
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        }
    }

    /** @return array<int,string>|null  data URIs JPEG, o null si algo falló */
    protected function encodePhotos(): ?array
    {
        if (! $this->photos) {
            return [];
        }

        if (! extension_loaded('gd')) {
            $this->addError('newPhotos', 'Photo processing is not available on this server.');
            return null;
        }

        $out = [];
        foreach ($this->photos as $file) {
            $data = $this->encodePhoto((string) $file->getRealPath());
            if ($data === null) {
                $this->addError('newPhotos', 'One photo could not be processed. Remove it and try another.');
                return null;
            }
            $out[] = $data;
        }

        return $out;
    }

    protected function encodePhoto(string $path): ?string
    {
        $info = @getimagesize($path);
        if (! $info || ($info[0] * $info[1]) > 25000000) {
            return null;
        }

        $img = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            default => false,
        };
        if (! $img) {
            return null;
        }

        // Respeta la orientación de las fotos de móvil (se pierde el EXIF al re-codificar).
        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($path);
            $angle = match ($exif['Orientation'] ?? 1) {
                3 => 180,
                6 => -90,
                8 => 90,
                default => 0,
            };
            if ($angle !== 0) {
                $rotated = imagerotate($img, $angle, 0);
                if ($rotated) {
                    $img = $rotated;
                }
            }
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $scale = min(1, 1280 / max($w, $h));
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $dst = imagecreatetruecolor($nw, $nh);
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        imagecopyresampled($dst, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);

        ob_start();
        imagejpeg($dst, null, 80);
        $jpeg = ob_get_clean();

        return $jpeg ? 'data:image/jpeg;base64,' . base64_encode($jpeg) : null;
    }

    public function pickTemplate(string $key): void
    {
        if (array_key_exists($key, $this->templates)) {
            $this->template = $key;
        }
    }

    public function next(): void
    {
        if ($this->step === 1 && ! \App\Support\Templates::canUse($this->template, auth()->user())) {
            $this->addError('template', 'This is a premium template. Unlock it to continue.');
            return;
        }

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
        if (! \App\Support\Templates::canUse($this->template, auth()->user())) {
            $this->addError('template', 'This is a premium template. Unlock it first.');
            $this->step = 1;
            return;
        }

        if (! $this->validateContent()) {
            $this->step = 2;
            return;
        }

        $this->validate([
            'opens_at' => 'nullable|date',
            'keep' => 'required|in:7,30,90,365',
            'tz' => 'nullable|string|max:64',
        ]);

        $plan = $this->plan();
        if ((int) $this->keep > $plan->max_days) {
            $this->addError('keep', 'Your plan allows up to ' . $plan->max_days . ' days.');
            return;
        }
        if (count($this->photos) > $plan->max_photos) {
            $this->addError('newPhotos', 'Your plan allows up to ' . $plan->max_photos . ' photos.');
            $this->step = 2;
            return;
        }

        $photos = $this->encodePhotos();
        if ($photos === null) {
            $this->step = 2;
            return;
        }

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
                'photos' => $photos,
                'music' => MusicLink::clean($this->music),
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

        // No dejar las fotos originales sin cifrar en el disco temporal.
        foreach ($this->photos as $file) {
            try {
                $file->delete();
            } catch (\Throwable $e) {
            }
        }

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
        return view('livewire.letters.wizard', ['plan' => $this->plan()]);
    }
}
