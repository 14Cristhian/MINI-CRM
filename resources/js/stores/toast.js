import { defineStore } from 'pinia'
import { ref } from 'vue'

let nextId = 0

export const useToastStore = defineStore('toast', () => {
    const toasts = ref([])

    function add(type, message, duration = 4000) {
        const id = ++nextId
        toasts.value.push({ id, type, message })
        if (duration > 0) setTimeout(() => remove(id), duration)
        return id
    }

    function remove(id) {
        toasts.value = toasts.value.filter((t) => t.id !== id)
    }

    const success = (msg, d) => add('success', msg, d)
    const error   = (msg, d) => add('error',   msg, d)
    const warning = (msg, d) => add('warning', msg, d)
    const info    = (msg, d) => add('info',    msg, d)

    return { toasts, add, remove, success, error, warning, info }
})
