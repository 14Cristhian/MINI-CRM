import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import http from '../axios'

export const useClientStore = defineStore('clients', () => {
    const clients = ref([])
    const client  = ref(null)
    const loading = ref(false)
    const error   = ref(null)

    const pagination = reactive({
        currentPage: 1,
        lastPage:    1,
        perPage:     15,
        total:       0,
    })

    const filters = reactive({ search: '', status: '' })

    async function fetchClients(page = 1) {
        loading.value = true
        error.value   = null
        try {
            const params = { page }
            if (filters.search) params.search = filters.search
            if (filters.status) params.status = filters.status

            const { data } = await http.get('/api/clients', { params })
            clients.value = data.data
            Object.assign(pagination, data.meta)
        } catch (e) {
            error.value = e.response?.data?.message ?? 'Error al cargar clientes.'
        } finally {
            loading.value = false
        }
    }

    async function fetchClient(id) {
        loading.value = true
        error.value   = null
        try {
            const { data } = await http.get(`/api/clients/${id}`)
            client.value = data.data
        } catch (e) {
            error.value = e.response?.data?.message ?? 'Error al cargar el cliente.'
        } finally {
            loading.value = false
        }
    }

    async function createClient(payload) {
        const { data } = await http.post('/api/clients', payload)
        return data
    }

    async function updateClient(id, payload) {
        const { data } = await http.put(`/api/clients/${id}`, payload)
        return data
    }

    async function deleteClient(id) {
        await http.delete(`/api/clients/${id}`)
        clients.value = clients.value.filter((c) => c.id !== id)
        pagination.total = Math.max(0, pagination.total - 1)
    }

    function resetFilters() {
        filters.search = ''
        filters.status = ''
    }

    return {
        clients, client, loading, error, pagination, filters,
        fetchClients, fetchClient, createClient, updateClient, deleteClient, resetFilters,
    }
})
