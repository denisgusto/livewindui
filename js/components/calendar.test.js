import { describe, it, expect } from 'vitest'
import lwCalendar, { buildDays } from './calendar.js'

describe('buildDays', () => {
    it('pads leading blanks and lists every day', () => {
        // Fevereiro de 2024 (bissexto) começa numa quinta (getDay = 4).
        const cells = buildDays(2024, 1)
        expect(cells).toHaveLength(4 + 29)
        expect(cells.slice(0, 4)).toEqual([null, null, null, null])
        expect(cells[4]).toBe(1)
        expect(cells[cells.length - 1]).toBe(29)
    })

    it('handles a month starting on Monday', () => {
        // Janeiro de 2024 começa numa segunda (getDay = 1).
        const cells = buildDays(2024, 0)
        expect(cells[0]).toBeNull()
        expect(cells[1]).toBe(1)
        expect(cells.filter((c) => c !== null)).toHaveLength(31)
    })
})

describe('lwCalendar', () => {
    it('initializes view + selection from value', () => {
        const c = lwCalendar({ value: '2024-01-15' })
        expect(c.viewYear).toBe(2024)
        expect(c.viewMonth).toBe(0)
        expect(c.selected).toBe('2024-01-15')
    })

    it('formats iso dates and detects the selected day', () => {
        const c = lwCalendar({ value: '2024-01-15' })
        expect(c.iso(5)).toBe('2024-01-05')
        expect(c.isSelected(15)).toBe(true)
        expect(c.isSelected(16)).toBe(false)
    })

    it('navigates months with year wrapping', () => {
        const c = lwCalendar({ value: '2024-01-10' })
        c.prevMonth()
        expect([c.viewYear, c.viewMonth]).toEqual([2023, 11])
        c.nextMonth()
        expect([c.viewYear, c.viewMonth]).toEqual([2024, 0])
    })

    it('select updates the value (no input ref = no-op sync)', () => {
        const c = lwCalendar({ value: '2024-01-10' })
        c.select(20)
        expect(c.selected).toBe('2024-01-20')
    })
})
