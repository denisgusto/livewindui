{{-- Card: wrapper de conteudo com slots opcionais header/footer. Props: variant=default|bordered|elevated. Uso: <x-livewind::card><x-slot:header>Titulo</x-slot:header>Conteudo</x-livewind::card> --}}
@props([])

<section {{ $attributes->class($classes()) }}>
    @isset($header)
        <div class="border-b border-border px-5 py-4">
            {{ $header }}
        </div>
    @endisset

    <div class="px-5 py-4">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-border bg-muted px-5 py-3">
            {{ $footer }}
        </div>
    @endisset
</section>
