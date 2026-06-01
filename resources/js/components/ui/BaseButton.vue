<script setup>
const props = defineProps({
    variant:  { type: String,  default: 'primary' },
    size:     { type: String,  default: 'md' },
    loading:  { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    type:     { type: String,  default: 'button' },
    icon:     { type: String,  default: '' },
})

const VARIANTS = {
    primary: `bg-[var(--color-brand-600)] hover:bg-[var(--color-brand-700)] text-white border-transparent shadow-xs`,
    secondary: `bg-[var(--bg-surface)] hover:bg-[var(--bg-surface-2)] text-[var(--text-primary)] border-[var(--border-default)]`,
    danger: `bg-[var(--color-error)] hover:brightness-90 text-white border-transparent shadow-xs`,
    ghost: `bg-transparent hover:bg-[var(--bg-surface-2)] text-[var(--text-secondary)] border-transparent`,
}

const SIZES = {
    xs: 'px-2.5 py-1 text-xs gap-1.5',
    sm: 'px-3   py-1.5 text-xs gap-1.5',
    md: 'px-4   py-2   text-sm gap-2',
    lg: 'px-5   py-2.5 text-sm gap-2',
}
</script>

<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="[
            'inline-flex items-center justify-center font-medium rounded-lg border',
            'transition-all focus:outline-none focus-visible:ring-[3px] focus-visible:ring-[rgba(99,102,241,.3)]',
            'select-none',
            VARIANTS[variant] ?? VARIANTS.primary,
            SIZES[size] ?? SIZES.md,
            (disabled || loading) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
        ]"
    >
        <svg
            v-if="loading"
            class="animate-spin -ml-0.5 h-3.5 w-3.5"
            fill="none" viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
        <span v-else-if="icon" v-html="icon" class="w-4 h-4 shrink-0" />
        <slot />
    </button>
</template>
