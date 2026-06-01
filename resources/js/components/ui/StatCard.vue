<script setup>
defineProps({
    label: { type: String,              required: true },
    value: { type: [String, Number],    required: true },
    icon:  { type: [Object, Function],  default: null  },
    color: { type: String,              default: 'brand' },
})

const COLORS = {
    brand:   { bg: 'var(--color-brand-50)',   icon: 'var(--color-brand-600)' },
    success: { bg: 'var(--color-success-bg)', icon: 'var(--color-success)' },
    warning: { bg: 'var(--color-warning-bg)', icon: 'var(--color-warning)' },
    error:   { bg: 'var(--color-error-bg)',   icon: 'var(--color-error)' },
    info:    { bg: 'var(--color-info-bg)',    icon: 'var(--color-info)' },
}
</script>

<template>
    <div
        class="rounded-2xl border p-5 transition-shadow hover:shadow-md"
        style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-sm)"
    >
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--text-muted)">
                    {{ label }}
                </p>
                <p class="text-3xl font-bold leading-none" style="color: var(--text-primary)">
                    {{ value }}
                </p>
            </div>

            <div
                v-if="icon"
                class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                :style="{ background: COLORS[color]?.bg ?? COLORS.brand.bg }"
            >
                <component
                    :is="icon"
                    class="w-5 h-5"
                    :style="{ color: COLORS[color]?.icon ?? COLORS.brand.icon }"
                />
            </div>
        </div>
    </div>
</template>
