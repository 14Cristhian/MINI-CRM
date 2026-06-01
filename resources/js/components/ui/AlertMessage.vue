<script setup>
defineProps({
    type:    { type: String, default: 'error' },
    message: { type: String, default: '' },
})

const CLASSES = {
    error:   'bg-red-50 border-red-200 text-red-700',
    success: 'bg-green-50 border-green-200 text-green-700',
    info:    'bg-blue-50 border-blue-200 text-blue-700',
}
</script>

<template>
    <div
        v-if="message"
        :class="['p-4 border rounded-lg text-sm', CLASSES[type] ?? CLASSES.error]"
    >
        {{ message }}
    </div>
</template>
