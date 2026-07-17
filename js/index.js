/**
 * LivewindUI runtime bundle.
 *
 * Servido via @livewindScripts como um <script> auto-executável, rodando sobre o
 * Alpine que o Livewire já carrega. Registra os Alpine.data() dos componentes com
 * lógica de estado (toast, calendar, signature) no evento alpine:init. Componentes
 * com Alpine trivial continuam inline no Blade.
 */
import lwToast from './components/toast.js'
import lwCalendar from './components/calendar.js'
import lwSignature from './components/signature.js'

window.Livewind = window.Livewind || {}

document.addEventListener('alpine:init', () => {
    window.Alpine.data('lwToast', lwToast)
    window.Alpine.data('lwCalendar', lwCalendar)
    window.Alpine.data('lwSignature', lwSignature)
})
