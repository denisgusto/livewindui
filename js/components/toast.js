/**
 * lwToast — container global de notificações (Alpine.data).
 *
 * Recebe { duration, max, flashed } do Blade via x-data="lwToast(@js($config))".
 * Escuta os eventos livewind:toast / livewind:toast.show, expõe window.Livewind.toast
 * e semeia os toasts flashados na sessão. A lógica de fila/timer/dedupe vive aqui
 * (testável por vitest), não numa string Blade.
 */
export default function lwToast(config = {}) {
    return {
        toasts: [],
        nextId: 1,
        defaultDuration: config.duration ?? 4000,
        max: config.max ?? 5,

        init() {
            window.addEventListener('livewind:toast.show', (event) => this.add(event.detail))
            window.addEventListener('livewind:toast', (event) => this.add(event.detail))

            window.Livewind = window.Livewind || {}
            window.Livewind.toast = (payload) =>
                window.dispatchEvent(
                    new CustomEvent('livewind:toast', {
                        detail: typeof payload === 'string' ? { message: payload } : payload,
                    })
                )

            const flashed = config.flashed ?? []
            flashed.forEach((toast) => this.add(toast))
        },

        add(payload) {
            const raw = Array.isArray(payload) ? (payload[0] ?? {}) : (payload ?? {})
            const detail = typeof raw === 'string' ? { message: raw } : raw

            const duration =
                detail.duration !== undefined && detail.duration !== null
                    ? Number(detail.duration)
                    : this.defaultDuration
            const variant = detail.variant ?? 'info'
            const title = detail.title ?? detail.heading ?? null
            const message = detail.message ?? detail.text ?? ''

            const duplicate = this.toasts.find(
                (toast) =>
                    toast.variant === variant && toast.title === title && toast.message === message
            )
            if (duplicate) {
                if (duplicate.timer) clearTimeout(duplicate.timer)
                duplicate.timer = null
                duplicate.remaining = duplicate.duration
                this.startTimer(duplicate)
                return
            }

            const toast = {
                id: this.nextId++,
                variant,
                title,
                message,
                duration,
                remaining: duration,
                startedAt: null,
                timer: null,
                visible: false,
            }

            this.toasts.push(toast)
            if (this.max > 0) {
                while (this.toasts.length > this.max) {
                    this.remove(this.toasts[0].id)
                }
            }

            const added = this.toasts[this.toasts.length - 1]
            const tick = this.$nextTick ?? ((callback) => callback())
            tick(() => {
                added.visible = true
            })
            this.startTimer(added)
        },

        startTimer(toast) {
            if (!toast.duration || toast.duration <= 0) return
            toast.startedAt = Date.now()
            toast.timer = setTimeout(() => this.remove(toast.id), toast.remaining)
        },

        pause(toast) {
            if (!toast.timer) return
            clearTimeout(toast.timer)
            toast.timer = null
            toast.remaining -= Date.now() - toast.startedAt
        },

        resume(toast) {
            if (toast.timer || !toast.duration || toast.duration <= 0) return
            this.startTimer(toast)
        },

        remove(id) {
            const toast = this.toasts.find((toast) => toast.id === id)
            if (toast && toast.timer) clearTimeout(toast.timer)
            this.toasts = this.toasts.filter((toast) => toast.id !== id)
        },

        roleFor(variant) {
            return ['warning', 'danger'].includes(variant) ? 'alert' : 'status'
        },
    }
}
