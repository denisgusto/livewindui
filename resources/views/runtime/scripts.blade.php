{{-- @livewindScripts: runtime global da lib. Renderize UMA vez, antes de </body>.
     Carrega o bundle JS servido (dist/livewind.js) e monta o container global de toasts
     (que registra window.Livewind.toast), usando os defaults de config('livewind.toast').
     Para posicionar o toast manualmente, use <x-livewind::toast ... /> direto. --}}
<script src="{{ route('livewind.js') }}" data-navigate-once></script>

<x-livewind::toast />
