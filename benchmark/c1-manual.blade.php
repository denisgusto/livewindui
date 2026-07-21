<form wire:submit="salvar" class="space-y-4">
    <div>
        <label for="nome" class="mb-1 block text-sm font-medium text-surface-foreground">Nome</label>
        <input
            id="nome"
            type="text"
            wire:model.live="nome"
            @error('nome') aria-invalid="true" aria-describedby="nome-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('nome') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('nome')
            <p id="nome-desc" class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="mb-1 block text-sm font-medium text-surface-foreground">E-mail</label>
        <input
            id="email"
            type="email"
            wire:model.live="email"
            @error('email') aria-invalid="true" aria-describedby="email-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('email') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('email')
            <p id="email-desc" class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="telefone" class="mb-1 block text-sm font-medium text-surface-foreground">Telefone</label>
        <input
            id="telefone"
            type="text"
            wire:model.live="telefone"
            @error('telefone') aria-invalid="true" aria-describedby="telefone-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('telefone') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('telefone')
            <p id="telefone-desc" class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="empresa" class="mb-1 block text-sm font-medium text-surface-foreground">Empresa</label>
        <input
            id="empresa"
            type="text"
            wire:model.live="empresa"
            @error('empresa') aria-invalid="true" aria-describedby="empresa-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('empresa') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('empresa')
            <p id="empresa-desc" class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="cargo" class="mb-1 block text-sm font-medium text-surface-foreground">Cargo</label>
        <input
            id="cargo"
            type="text"
            wire:model.live="cargo"
            @error('cargo') aria-invalid="true" aria-describedby="cargo-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('cargo') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('cargo')
            <p id="cargo-desc" class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="site" class="mb-1 block text-sm font-medium text-surface-foreground">Site</label>
        <input
            id="site"
            type="url"
            wire:model.live="site"
            @error('site') aria-invalid="true" aria-describedby="site-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('site') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('site')
            <p id="site-desc" class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="cidade" class="mb-1 block text-sm font-medium text-surface-foreground">Cidade</label>
        <input
            id="cidade"
            type="text"
            wire:model.live="cidade"
            @error('cidade') aria-invalid="true" aria-describedby="cidade-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('cidade') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('cidade')
            <p id="cidade-desc" class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="cep" class="mb-1 block text-sm font-medium text-surface-foreground">CEP</label>
        <input
            id="cep"
            type="text"
            wire:model.live="cep"
            @error('cep') aria-invalid="true" aria-describedby="cep-desc" @enderror
            class="block w-full rounded-md border bg-surface px-3 py-2 text-sm text-surface-foreground shadow-sm transition focus:outline-none focus:ring-2 @error('cep') border-danger focus:ring-danger @else border-border focus:ring-accent @enderror"
        />
        @error('cep')
            <p id="cep-desc" class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <button
        type="submit"
        wire:loading.attr="disabled"
        wire:target="salvar"
        class="inline-flex items-center justify-center gap-2 rounded-md bg-accent px-4 py-2 text-sm font-medium text-accent-foreground transition hover:bg-accent-content focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent disabled:opacity-70"
    >
        <span wire:loading wire:target="salvar" class="contents" aria-busy="true">
            <svg class="size-4 shrink-0 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
            </svg>
        </span>
        <span wire:loading.class="opacity-70" wire:target="salvar">Salvar</span>
    </button>
</form>
