import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query'
import { fetchContacts, createContact, updateContact, deleteContact } from '../api/contacts'
import { useToastStore } from '../stores/toast'

export const contactKeys = {
    all:    (clientId) => ['contacts', clientId],
    list:   (clientId) => ['contacts', clientId, 'list'],
}

export function useContactsList(clientId) {
    return useQuery({
        queryKey: contactKeys.list(clientId),
        queryFn:  ({ signal }) => fetchContacts(clientId, signal),
        staleTime: 30_000,
        retry: 1,
    })
}

export function useContactMutations(clientId) {
    const qc    = useQueryClient()
    const toast = useToastStore()

    const invalidate = () => qc.invalidateQueries({ queryKey: contactKeys.list(clientId) })

    const create = useMutation({
        mutationFn: (payload) => createContact(clientId, payload),
        onSuccess: () => { invalidate() },
        onError:   () => { toast.error('No se pudo crear el contacto.') },
    })

    const update = useMutation({
        mutationFn: ({ contactId, payload }) => updateContact(clientId, contactId, payload),
        onSuccess: () => { invalidate() },
        onError:   () => { toast.error('No se pudo actualizar el contacto.') },
    })

    const remove = useMutation({
        mutationFn: (contactId) => deleteContact(clientId, contactId),
        onSuccess: () => { invalidate() },
        onError:   () => { toast.error('No se pudo eliminar el contacto.') },
    })

    return { create, update, remove }
}
