import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'
import lwToast from './toast.js'

describe('lwToast', () => {
    beforeEach(() => vi.useFakeTimers())
    afterEach(() => vi.useRealTimers())

    it('adds a toast from a string payload', () => {
        const t = lwToast()
        t.add('Ola')
        expect(t.toasts).toHaveLength(1)
        expect(t.toasts[0].message).toBe('Ola')
        expect(t.toasts[0].variant).toBe('info')
    })

    it('supports the flux aliases text/heading', () => {
        const t = lwToast()
        t.add({ heading: 'Titulo', text: 'Corpo' })
        expect(t.toasts[0].title).toBe('Titulo')
        expect(t.toasts[0].message).toBe('Corpo')
    })

    it('dedupes identical toasts', () => {
        const t = lwToast()
        t.add({ message: 'x', variant: 'info' })
        t.add({ message: 'x', variant: 'info' })
        expect(t.toasts).toHaveLength(1)
    })

    it('caps the stack at max, dropping the oldest', () => {
        const t = lwToast({ max: 2 })
        t.add('a')
        t.add('b')
        t.add('c')
        expect(t.toasts.map((x) => x.message)).toEqual(['b', 'c'])
    })

    it('auto-dismisses after the duration', () => {
        const t = lwToast({ duration: 1000 })
        t.add('bye')
        expect(t.toasts).toHaveLength(1)
        vi.advanceTimersByTime(1000)
        expect(t.toasts).toHaveLength(0)
    })

    it('treats duration 0 as permanent', () => {
        const t = lwToast()
        t.add({ message: 'stay', duration: 0 })
        vi.advanceTimersByTime(100000)
        expect(t.toasts).toHaveLength(1)
    })

    it('pause/resume adjusts the remaining time', () => {
        const t = lwToast({ duration: 1000 })
        t.add('p')
        const toast = t.toasts[0]

        vi.advanceTimersByTime(400)
        t.pause(toast)
        vi.advanceTimersByTime(10000)
        expect(t.toasts).toHaveLength(1)

        t.resume(toast)
        vi.advanceTimersByTime(600)
        expect(t.toasts).toHaveLength(0)
    })

    it('maps warning/danger to role=alert, else status', () => {
        const t = lwToast()
        expect(t.roleFor('warning')).toBe('alert')
        expect(t.roleFor('danger')).toBe('alert')
        expect(t.roleFor('info')).toBe('status')
        expect(t.roleFor('success')).toBe('status')
    })
})
