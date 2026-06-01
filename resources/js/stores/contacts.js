import { defineStore } from 'pinia'
import { ref } from 'vue'
import http from '../axios'

export const useContactStore = defineStore('contacts', () => {
    const contacts = ref([])
    const loading  = ref(false)
    const error    = ref(null)

    async function fetchContacts(clientId) {
        loading.value = true
        error.value   = null
        try {
            const { data } = await http.get(`/api/clients/${clientId}/contacts`)
            contacts.value = data.data
        } catch (e) {
            error.value = e.response?.data?.message ?? 'Error al cargar contactos.'
        } finally {
            loading.value = false
        }
    }

    async function createContact(clientId, payload) {
        const { data } = await http.post(`/api/clients/${clientId}/contacts`, payload)
        contacts.value.push(data.data)
        if (data.data.is_primary) {
            contacts.value.forEach((c) => {
                if (c.id !== data.data.id) c.is_primary = false
            })
        }
        return data
    }

    async function updateContact(clientId, contactId, payload) {
        const { data } = await http.put(`/api/clients/${clientId}/contacts/${contactId}`, payload)
        const index = contacts.value.findIndex((c) => c.id === contactId)
        if (index !== -1) {
            contacts.value[index] = data.data
        }
        if (data.data.is_primary) {
            contacts.value.forEach((c) => {
                if (c.id !== contactId) c.is_primary = false
            })
        }
        return data
    }

    async function deleteContact(clientId, contactId) {
        await http.delete(`/api/clients/${clientId}/contacts/${contactId}`)
        contacts.value = contacts.value.filter((c) => c.id !== contactId)
    }

    return {
        contacts,
        loading,
        error,
        fetchContacts,
        createContact,
        updateContact,
        deleteContact,
    }
})
