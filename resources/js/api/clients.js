import http from '../axios'

/**
 * Capa de API para clientes.
 * Todas las funciones devuelven los datos ya desenvueltos (sin el wrapper { success, data }).
 * AbortSignal se pasa desde TanStack Query automáticamente para cancelar
 * requests cuando la query key cambia antes de que el anterior termine.
 */

export async function fetchClients({ search, status, page, sort_by, sort_dir } = {}, signal) {
    const params = { page: page ?? 1 }
    if (search)   params.search   = search
    if (status)   params.status   = status
    if (sort_by)  params.sort_by  = sort_by
    if (sort_dir) params.sort_dir = sort_dir

    const { data } = await http.get('/api/clients', { params, signal })
    return data
}

export async function fetchClient(id, signal) {
    const { data } = await http.get(`/api/clients/${id}`, { signal })
    return data.data
}

export async function createClient(payload) {
    const { data } = await http.post('/api/clients', payload)
    return data
}

export async function updateClient(id, payload) {
    const { data } = await http.put(`/api/clients/${id}`, payload)
    return data
}

export async function deleteClient(id) {
    const { data } = await http.delete(`/api/clients/${id}`)
    return data
}
