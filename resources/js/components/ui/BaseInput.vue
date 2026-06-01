<script setup>
defineProps({
    modelValue:  { default: '' },
    label:       { type: String,  default: '' },
    placeholder: { type: String,  default: '' },
    type:        { type: String,  default: 'text' },
    error:       { type: String,  default: '' },
    required:    { type: Boolean, default: false },
    disabled:    { type: Boolean, default: false },
    hint:        { type: String,  default: '' },
})

defineEmits(['update:modelValue'])

const inputId = `input-${Math.random().toString(36).slice(2, 8)}`
</script>

<template>
    <div class="flex flex-col gap-1">
        <label
            v-if="label"
            :for="inputId"
            class="text-xs font-medium"
            style="color: var(--text-secondary)"
        >
            {{ label }}
            <span v-if="required" style="color: var(--color-error)" class="ml-0.5">*</span>
        </label>

        <div class="relative">
            <span
                v-if="$slots.icon"
                class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none flex items-center"
                style="color: var(--text-muted)"
            >
                <slot name="icon" />
            </span>

            <input
                :id="inputId"
                :type="type"
                :value="modelValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :required="required"
                :class="[
                    'w-full text-sm rounded-lg px-3 py-2 transition-all',
                    'bg-[var(--bg-surface)] text-[var(--text-primary)]',
                    'border placeholder:text-[var(--text-placeholder)]',
                    'focus:outline-none focus:ring-[3px]',
                    $slots.icon ? 'pl-9' : '',
                    error
                        ? 'border-[var(--color-error)] focus:ring-[rgba(220,38,38,.2)]'
                        : 'border-[var(--border-default)] focus:border-[var(--color-brand-500)] focus:ring-[rgba(99,102,241,.2)]',
                    disabled ? 'opacity-50 cursor-not-allowed' : '',
                ]"
                @input="$emit('update:modelValue', $event.target.value)"
            >
        </div>

        <p v-if="error" class="text-xs" style="color: var(--color-error)">{{ error }}</p>
        <p v-else-if="hint" class="text-xs" style="color: var(--text-muted)">{{ hint }}</p>
    </div>
</template>
