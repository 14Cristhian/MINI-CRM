import { reactive, ref } from 'vue'

export function useForm(initialData) {
    const form    = reactive({ ...initialData })
    const errors  = reactive({})
    const loading = ref(false)

    function setErrors(err) {
        clearErrors()
        const bag = err.response?.data?.errors ?? {}
        Object.assign(errors, bag)
    }

    function clearErrors() {
        Object.keys(errors).forEach((k) => delete errors[k])
    }

    function reset() {
        clearErrors()
        Object.keys(initialData).forEach((k) => { form[k] = initialData[k] })
    }

    function fill(data) {
        Object.keys(form).forEach((k) => {
            if (data[k] !== undefined) form[k] = data[k] ?? ''
        })
    }

    async function submit(fn) {
        loading.value = true
        clearErrors()
        try {
            return await fn(form)
        } catch (e) {
            setErrors(e)
            throw e
        } finally {
            loading.value = false
        }
    }

    return { form, errors, loading, reset, fill, submit, setErrors, clearErrors }
}
