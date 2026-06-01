<script setup>
defineProps({
    status: { type: String, required: true },
})

const BADGE = {
    activo:    'bg-[var(--color-success-bg)] text-[var(--color-success)]',
    inactivo:  'bg-[var(--bg-surface-3)]     text-[var(--text-muted)]',
    prospecto: 'bg-[var(--color-warning-bg)] text-[var(--color-warning)]',
}

const DOT = {
    activo:    'bg-[var(--color-success)] animate-pulse',
    inactivo:  'bg-[var(--text-muted)]',
    prospecto: 'bg-[var(--color-warning)]',
}
</script>

<template>
    <span
        :class="['inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium capitalize', BADGE[status] ?? 'bg-[var(--bg-surface-3)] text-[var(--text-muted)]']"
    >
        <span :class="['w-1.5 h-1.5 rounded-full shrink-0', DOT[status] ?? 'bg-[var(--text-muted)]']" />
        {{ status }}
    </span>
</template>
