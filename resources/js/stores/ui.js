import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export const useUiStore = defineStore('ui', () => {
    const sidebarOpen = ref(window.innerWidth >= 1024)
    const theme       = ref(localStorage.getItem('theme') ?? 'light')

    watch(theme, (val) => {
        localStorage.setItem('theme', val)
        document.documentElement.setAttribute('data-theme', val)
    }, { immediate: true })

    function toggleTheme() {
        theme.value = theme.value === 'light' ? 'dark' : 'light'
    }

    function toggleSidebar() {
        sidebarOpen.value = !sidebarOpen.value
    }

    return { sidebarOpen, theme, toggleTheme, toggleSidebar }
})
