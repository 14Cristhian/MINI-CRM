import { computed, ref, watch } from "vue";
import { useQuery, useMutation, useQueryClient } from "@tanstack/vue-query";
import { useDebounce } from "./useDebounce";
import {
    fetchClients,
    fetchClient,
    createClient,
    updateClient,
    deleteClient,
} from "../api/clients";
import { useToastStore } from "../stores/toast";

export const clientKeys = {
    all: () => ["clients"],
    lists: () => ["clients", "list"],
    list: (filters) => ["clients", "list", filters],
    detail: (id) => ["clients", "detail", id],
};

// URL helpers
function readUrlParams() {
    const params = new URLSearchParams(window.location.search);
    return {
        search: params.get("search") ?? "",
        status: params.get("status") ?? "",
        sort_by: params.get("sort_by") ?? "created_at",
        sort_dir: params.get("sort_dir") ?? "desc",
        page: Math.max(1, parseInt(params.get("page") ?? "1")),
    };
}

function syncUrl({ search, status, sort_by, sort_dir, page }) {
    const params = new URLSearchParams();
    if (search) params.set("search", search);
    if (status) params.set("status", status);
    if (sort_by !== "created_at") params.set("sort_by", sort_by);
    if (sort_dir !== "desc") params.set("sort_dir", sort_dir);
    if (page > 1) params.set("page", String(page));

    const qs = params.toString();
    const url = window.location.pathname + (qs ? "?" + qs : "");
    window.history.replaceState({}, "", url);
}

export function useClientsList() {
    const initial = readUrlParams();

    const searchInput = ref(initial.search);
    const statusFilter = ref(initial.status);
    const page = ref(initial.page);
    const sortBy = ref(initial.sort_by);
    const sortDir = ref(initial.sort_dir);

    const debouncedSearch = useDebounce(searchInput, 400);

    const queryKey = computed(() =>
        clientKeys.list({
            search: debouncedSearch.value,
            status: statusFilter.value,
            page: page.value,
            sort_by: sortBy.value,
            sort_dir: sortDir.value,
        }),
    );

    const query = useQuery({
        queryKey,
        queryFn: ({ signal }) =>
            fetchClients(
                {
                    search: debouncedSearch.value,
                    status: statusFilter.value,
                    page: page.value,
                    sort_by: sortBy.value,
                    sort_dir: sortDir.value,
                },
                signal,
            ),
        placeholderData: (prev) => prev,
        staleTime: 30_000,
        retry: 1,
    });

    watch([debouncedSearch, statusFilter, page, sortBy, sortDir], () =>
        syncUrl({
            search: debouncedSearch.value,
            status: statusFilter.value,
            sort_by: sortBy.value,
            sort_dir: sortDir.value,
            page: page.value,
        }),
    );

    function setSearch(val) {
        searchInput.value = val;
        page.value = 1;
    }
    function setStatus(val) {
        statusFilter.value = val;
        page.value = 1;
    }

    function setSort(col) {
        if (sortBy.value === col) {
            sortDir.value = sortDir.value === "asc" ? "desc" : "asc";
        } else {
            sortBy.value = col;
            sortDir.value = "asc";
        }
        page.value = 1;
    }

    function clearFilters() {
        searchInput.value = "";
        statusFilter.value = "";
        page.value = 1;
    }

    const clients = computed(() => query.data.value?.data ?? []);
    const meta = computed(
        () =>
            query.data.value?.meta ?? {
                total: 0,
                last_page: 1,
                current_page: 1,
            },
    );
    const isLoading = computed(() => query.isLoading.value);
    const isFetching = computed(() => query.isFetching.value);

    return {
        searchInput,
        statusFilter,
        page,
        sortBy,
        sortDir,
        debouncedSearch,
        clients,
        meta,
        isLoading,
        isFetching,
        setSearch,
        setStatus,
        setSort,
        clearFilters,
        goToPage: (p) => {
            page.value = p;
        },
    };
}

export function useClientDetail(id) {
    return useQuery({
        queryKey: clientKeys.detail(id),
        queryFn: ({ signal }) => fetchClient(id, signal),
        staleTime: 60_000,
        retry: 1,
    });
}

export function useClientMutations() {
    const qc = useQueryClient();
    const toast = useToastStore();

    const invalidate = () =>
        qc.invalidateQueries({ queryKey: clientKeys.lists() });

    const create = useMutation({
        mutationFn: (payload) => createClient(payload),
        onSuccess: () => {
            invalidate();
        },
        onError: () => {
            toast.error("No se pudo crear el cliente.");
        },
    });

    const update = useMutation({
        mutationFn: ({ id, payload }) => updateClient(id, payload),
        onSuccess: (_, { id }) => {
            invalidate();
            qc.invalidateQueries({ queryKey: clientKeys.detail(id) });
        },
        onError: () => {
            toast.error("No se pudo actualizar el cliente.");
        },
    });

    const remove = useMutation({
        mutationFn: (id) => deleteClient(id),
        onSuccess: () => {
            invalidate();
        },
        onError: () => {
            toast.error("No se pudo eliminar el cliente.");
        },
    });

    return { create, update, remove };
}
