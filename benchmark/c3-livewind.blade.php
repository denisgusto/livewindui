<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($metricas as $metrica)
            <x-livewind::card>
                <x-slot:header>{{ $metrica['rotulo'] }}</x-slot:header>
                {{ $metrica['valor'] }}
            </x-livewind::card>
        @endforeach
    </div>

    <x-livewind::button wire:click="abrirDetalhes">Ver detalhes</x-livewind::button>

    <x-livewind::modal name="detalhes" title="Detalhes">
        {{ $detalhe }}
    </x-livewind::modal>
</div>
