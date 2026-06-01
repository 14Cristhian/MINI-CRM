<script setup>
defineProps({
    modelValue: { default: '' },
    label:      { type: String,  default: '' },
    options:    { type: Array,   default: () => [] },
    placeholder:{ type: String,  default: 'Seleccionar...' },
    error:      { type: String,  default: '' },
    required:   { type: Boolean, default: false },
    disabled:   { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])

const selectId = `select-${Math.random().toString(36).slice(2, 8)}`
</script>

<template>
    <div class="flex flex-col gap-1">
        <label
            v-if="label"
            :for="selectId"
            class="text-xs font-medium"
            :style="{ color: 'var(--text-secondary)' }"
        >
            {{ label }}
            <span v-if="required" class="text-[var(--color-error)] ml-0.5">*</span>
        </label>

        <div class="relative">
            <select
                :id="selectId"
                :value="modelValue"
                :disabled="disabled"
                :class="[
                    'w-full text-sm rounded-lg px-3 py-2 pr-8 appearance-none transition-all',
                    'bg-[var(--bg-surface)] text-[var(--text-primary)]',
                    'border focus:outline-none focus:ring-[3px] cursor-pointer',
                    error
                        ? 'border-[var(--color-error)] focus:ring-[rgba(220,38,38,.2)]'
                        : 'border-[var(--border-default)] focus:border-[var(--color-brand-500)] focus:ring-[rgba(99,102,241,.2)]',
                    disabled ? 'opacity-50 cursor-not-allowed' : '',
                ]"
                @change="$emit('update:modelValue', $event.target.value)"
            >
                <option value="">{{ placeholder }}</option>
                <option
                    v-for="opt in options"
                    :key="opt.value"
                    :value="opt.value"
                >
                    {{ opt.label }}
                </option>
            </select>

            <svg
                class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-[var(--text-muted)]"
                width="14" height="14" viewBox="0 0 20 20" fill="currentColor"
            >
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 011.06 0L10 11.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 9.28a.75.75 0 010-1.06z" clip-rule="evenodd"/>
            </svg>
        </div>

        <p v-if="error" class="text-xs text-[var(--color-error)]">{{ error }}</p>
    </div>
</template>
