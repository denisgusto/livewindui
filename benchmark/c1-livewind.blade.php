<form wire:submit="salvar" class="space-y-4">
    <x-livewind::input model="nome" :model-live="true" label="Nome" type="text" />
    <x-livewind::input model="email" :model-live="true" label="E-mail" type="email" />
    <x-livewind::input model="telefone" :model-live="true" label="Telefone" type="text" />
    <x-livewind::input model="empresa" :model-live="true" label="Empresa" type="text" />
    <x-livewind::input model="cargo" :model-live="true" label="Cargo" type="text" />
    <x-livewind::input model="site" :model-live="true" label="Site" type="url" />
    <x-livewind::input model="cidade" :model-live="true" label="Cidade" type="text" />
    <x-livewind::input model="cep" :model-live="true" label="CEP" type="text" />

    <x-livewind::button type="submit" loading="salvar">Salvar</x-livewind::button>
</form>
