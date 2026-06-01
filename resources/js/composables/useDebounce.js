import { ref, watch } from "vue";

export function useDebounce(source, delay = 400) {
    const debounced = ref(typeof source === "object" ? source.value : source);
    let timer = null;

    watch(source, (val) => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            debounced.value = val;
        }, delay);
    });

    return debounced;
}
