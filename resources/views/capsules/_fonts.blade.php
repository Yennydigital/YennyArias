@include('capsules._fontfaces')
<style>
@foreach (\App\Support\LetterFonts::all() as $k => $f)
@if ($k !== 'default')
.letter[data-font="{{ $k }}"],.letter[data-font="{{ $k }}"] *:not(.btn){font-family:{!! $f['stack'] !!}!important}
@endif
@endforeach
</style>
