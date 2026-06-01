import http from '../axios'

export async function fetchContacts(clientId, signal) {
    const { data } = await http.get(`/api/clients/${clientId}/contacts`, { signal })
    return data.data
}

export async function createContact(clientId, payload) {
    const { data } = await http.post(`/api/clients/${clientId}/contacts`, payload)
    return data
}

export async function updateContact(clientId, contactId, payload) {
    const { data } = await http.put(`/api/clients/${clientId}/contacts/${contactId}`, payload)
    return data
}

export async function deleteContact(clientId, contactId) {
    const { data } = await http.delete(`/api/clients/${clientId}/contacts/${contactId}`)
    return data
}
