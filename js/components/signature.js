/**
 * lwSignature — assinatura em <canvas> (Alpine.data).
 *
 * Desenho via pointer events (mouse/touch/caneta), exporta PNG (data URL) e sincroniza
 * num <input hidden x-ref="input"> ligado por wire:model. Clear zera. A maior parte é
 * canvas-bound (validada no navegador); o vitest cobre o shape e o estado inicial.
 */
export default function lwSignature(config = {}) {
    return {
        drawing: false,
        empty: true,
        ctx: null,
        last: null,

        init() {
            const canvas = this.$refs.canvas
            const ratio = window.devicePixelRatio || 1
            const rect = canvas.getBoundingClientRect()
            canvas.width = rect.width * ratio
            canvas.height = rect.height * ratio

            this.ctx = canvas.getContext('2d')
            this.ctx.scale(ratio, ratio)
            this.ctx.lineWidth = config.penWidth ?? 2
            this.ctx.lineCap = 'round'
            this.ctx.lineJoin = 'round'
            this.ctx.strokeStyle = config.penColor ?? '#111827'
        },

        pos(event) {
            const rect = this.$refs.canvas.getBoundingClientRect()
            return { x: event.clientX - rect.left, y: event.clientY - rect.top }
        },

        start(event) {
            this.drawing = true
            this.last = this.pos(event)
        },

        move(event) {
            if (!this.drawing) return
            const point = this.pos(event)
            this.ctx.beginPath()
            this.ctx.moveTo(this.last.x, this.last.y)
            this.ctx.lineTo(point.x, point.y)
            this.ctx.stroke()
            this.last = point
            this.empty = false
        },

        stop() {
            if (!this.drawing) return
            this.drawing = false
            this.sync()
        },

        clear() {
            const canvas = this.$refs.canvas
            this.ctx.clearRect(0, 0, canvas.width, canvas.height)
            this.empty = true
            this.sync()
        },

        sync() {
            const input = this.$refs?.input
            if (!input) return
            input.value = this.empty ? '' : this.$refs.canvas.toDataURL('image/png')
            input.dispatchEvent(new Event('input', { bubbles: true }))
        },
    }
}
