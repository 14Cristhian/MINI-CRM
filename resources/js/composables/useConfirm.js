import { ref } from 'vue'

export function useConfirm() {
    const show    = ref(false)
    const loading = ref(false)
    const meta    = ref({})
    let   resolve = null

    function open(options = {}) {
        meta.value = options
        show.value = true
        return new Promise((res) => { resolve = res })
    }

    async function confirm() {
        loading.value = true
        try {
            if (meta.value.onConfirm) await meta.value.onConfirm()
            resolve(true)
        } finally {
            loading.value = false
            show.value    = false
        }
    }

    function cancel() {
        show.value = false
        resolve(false)
    }

    return { show, loading, meta, open, confirm, cancel }
}
