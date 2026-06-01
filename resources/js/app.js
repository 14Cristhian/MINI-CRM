import "./bootstrap";
import { createApp, h } from "vue";
import { createPinia } from "pinia";
import { VueQueryPlugin, QueryClient } from "@tanstack/vue-query";

import ClientList from "./components/clients/ClientList.vue";
import ClientForm from "./components/clients/ClientForm.vue";
import ClientDetail from "./components/clients/ClientDetail.vue";
import DashboardView from "./components/dashboard/DashboardView.vue";
import ToastContainer from "./components/ui/ToastContainer.vue";
import AppSidebar from "./components/layout/AppSidebar.vue";
import AppTopbar from "./components/layout/AppTopbar.vue";

// ── Instancias globales compartidas ─────────────────────────
const pinia = createPinia();

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            staleTime: 30_000, // 30s → no re-fetch innecesarios al navegar
            gcTime: 5 * 60_000, // 5min en cache (garbage collection)
            refetchOnWindowFocus: false, // no re-fetch al volver a la tab
            retry: 1,
        },
    },
});

// ── Función de montaje: todas las apps comparten pinia + queryClient ──
function mount(selector, Component, extraProps = {}) {
    const el = document.querySelector(selector);
    if (!el) return null;

    const app = createApp(Component, { ...parseDataset(el), ...extraProps });
    app.use(pinia);
    app.use(VueQueryPlugin, { queryClient });
    app.mount(el);
    return app;
}

function parseDataset(el) {
    const out = {};
    Object.entries(el.dataset).forEach(([k, v]) => {
        try {
            out[k] = JSON.parse(v);
        } catch {
            out[k] = v;
        }
    });
    return out;
}

// Layout
mount("#app-sidebar", AppSidebar);
mount("#app-topbar", AppTopbar);
mount("#app-toasts", ToastContainer);

// Vistas
mount("#dashboard-app", DashboardView);
mount("#client-list-app", ClientList);
mount("#client-create-app", ClientForm);
mount("#client-edit-app", ClientForm);
mount("#client-detail-app", ClientDetail);

// TanStack Query DevTools
if (import.meta.env.DEV) {
    import("@tanstack/vue-query-devtools").then(({ VueQueryDevtools }) => {
        const el = document.createElement("div");
        document.body.appendChild(el);
        createApp({
            render: () =>
                h(VueQueryDevtools, { buttonPosition: "bottom-right" }),
        })
            .use(VueQueryPlugin, { queryClient })
            .mount(el);
    });
}
