<x-umbra-layout title="Letters">
    <h1 class="title">Letters</h1>
    <p class="sub">Your letters live here.</p>

    <div class="grid">
        <a href="{{ route('letters.create') }}" class="tile ghost">
            <div class="ico"><i class="bi bi-plus-lg"></i></div>
            <h3>New letter</h3>
            <p>Write, preview and seal a new letter.</p>
        </a>

        @foreach ($letters as $letter)
            <div class="tile">
                <div class="ico"><i class="bi bi-envelope-heart"></i></div>
                <h3>{{ $letter->title ?: 'Untitled letter' }}</h3>
                <p>{{ ucfirst(str_replace('_', ' ', $letter->status)) }} · {{ $letter->created_at->diffForHumans() }}</p>
                <form method="POST" action="{{ route('letters.destroy', $letter) }}" onsubmit="return confirm('Delete this letter?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="margin-top:10px;background:none;border:0;color:#f87171;cursor:pointer;font-family:inherit;padding:0">Delete</button>
                </form>
            </div>
        @endforeach
    </div>
</x-umbra-layout>
