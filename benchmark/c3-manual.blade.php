<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($metricas as $metrica)
            <div class="rounded-lg border border-border bg-surface p-5 shadow-sm">
                <p class="text-sm text-muted-foreground">{{ $metrica['rotulo'] }}</p>
                <p class="mt-1 text-2xl font-semibold text-surface-foreground">{{ $metrica['valor'] }}</p>
            </div>
        @endforeach
    </div>

    <button
        type="button"
        wire:click="abrirDetalhes"
        class="inline-flex items-center justify-center rounded-md bg-accent px-4 py-2 text-sm font-medium text-accent-foreground transition hover:bg-accent-content focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
    >
        Ver detalhes
    </button>

    <div
        x-data="{ aberto: @entangle('mostrarDetalhes') }"
        x-show="aberto"
        x-on:keydown.escape.window="aberto = false"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="titulo-modal"
    >
        <div x-show="aberto" x-transition.opacity x-on:click="aberto = false" class="absolute inset-0 bg-black/50"></div>
        <div
            x-show="aberto"
            x-transition
            x-trap.noscroll="aberto"
            class="relative w-full max-w-md rounded-lg border border-border bg-surface p-6 shadow-lg"
        >
            <h2 id="titulo-modal" class="text-lg font-semibold text-surface-foreground">Detalhes</h2>
            <div class="mt-3 text-sm text-muted-foreground">
                {{ $detalhe }}
            </div>
            <div class="mt-6 flex justify-end">
                <button
                    type="button"
                    x-on:click="aberto = false"
                    class="rounded-md border border-border px-4 py-2 text-sm font-medium text-surface-foreground hover:bg-muted"
                >
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <div
        x-data="{ toasts: [] }"
        x-on:notificar.window="
            const id = Date.now();
            toasts.push({ id, mensagem: $event.detail.mensagem });
            setTimeout(() => toasts = toasts.filter(t => t.id !== id), 4000)
        "
        class="fixed top-4 right-4 z-50 flex w-80 flex-col gap-2"
        role="status"
        aria-live="polite"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition class="rounded-md border border-border bg-surface p-4 text-sm text-surface-foreground shadow-lg">
                <span x-text="toast.mensagem"></span>
            </div>
        </template>
    </div>
</div>
