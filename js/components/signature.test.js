import { describe, it, expect } from 'vitest'
import lwSignature from './signature.js'

// O grosso do signature é canvas-bound (validado no navegador). Aqui garantimos o
// shape do Alpine.data e o estado inicial; sync() sem input ref é no-op seguro.
describe('lwSignature', () => {
    it('starts empty and not drawing', () => {
        const s = lwSignature()
        expect(s.empty).toBe(true)
        expect(s.drawing).toBe(false)
    })

    it('exposes the expected api', () => {
        const s = lwSignature()
        for (const method of ['init', 'start', 'move', 'stop', 'clear', 'sync']) {
            expect(typeof s[method]).toBe('function')
        }
    })

    it('sync is a safe no-op without an input ref', () => {
        const s = lwSignature()
        s.$refs = {}
        expect(() => s.sync()).not.toThrow()
    })
})
