/**
 * lwCalendar — date picker de data única (Alpine.data).
 *
 * Recebe { value: 'YYYY-MM-DD'|null, locale } do Blade. Sincroniza a seleção num
 * <input hidden x-ref="input"> ligado por wire:model. A lógica de grade/navegação é
 * pura (testável por vitest via buildDays).
 */
export function buildDays(year, month) {
    const firstWeekday = new Date(year, month, 1).getDay() // 0 = domingo
    const totalDays = new Date(year, month + 1, 0).getDate()

    const cells = []
    for (let i = 0; i < firstWeekday; i++) {
        cells.push(null) // espaços antes do dia 1
    }
    for (let day = 1; day <= totalDays; day++) {
        cells.push(day)
    }

    return cells
}

export default function lwCalendar(config = {}) {
    const base = config.value ? new Date(`${config.value}T00:00:00`) : new Date()

    return {
        locale: config.locale ?? 'en',
        viewYear: base.getFullYear(),
        viewMonth: base.getMonth(), // 0-11
        selected: config.value ?? null,

        weekdays: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],

        get days() {
            return buildDays(this.viewYear, this.viewMonth)
        },

        get monthLabel() {
            return new Date(this.viewYear, this.viewMonth, 1).toLocaleDateString(this.locale, {
                month: 'long',
                year: 'numeric',
            })
        },

        iso(day) {
            const month = String(this.viewMonth + 1).padStart(2, '0')
            const date = String(day).padStart(2, '0')
            return `${this.viewYear}-${month}-${date}`
        },

        isSelected(day) {
            return Boolean(day) && this.selected === this.iso(day)
        },

        select(day) {
            if (!day) return
            this.selected = this.iso(day)
            this.sync()
        },

        prevMonth() {
            if (this.viewMonth === 0) {
                this.viewMonth = 11
                this.viewYear--
            } else {
                this.viewMonth--
            }
        },

        nextMonth() {
            if (this.viewMonth === 11) {
                this.viewMonth = 0
                this.viewYear++
            } else {
                this.viewMonth++
            }
        },

        sync() {
            const input = this.$refs?.input
            if (!input) return
            input.value = this.selected ?? ''
            input.dispatchEvent(new Event('input', { bubbles: true }))
        },
    }
}
