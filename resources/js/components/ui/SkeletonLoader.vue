<script setup>
defineProps({
    rows: { type: Number, default: 6 },
})
</script>

<template>
    <div>
        <!-- Header row -->
        <div
            class="grid px-5 py-3 gap-4 border-b"
            style="grid-template-columns: 1fr 160px 80px 48px 112px; background: var(--bg-surface-2); border-color: var(--border-subtle)"
        >
            <div class="skeleton h-2.5 rounded w-16" />
            <div class="skeleton h-2.5 rounded w-20 hidden md:block" />
            <div class="skeleton h-2.5 rounded w-14" />
            <div class="skeleton h-2.5 rounded w-8 hidden sm:block" />
            <div class="skeleton h-2.5 rounded w-12 ml-auto" />
        </div>

        <!-- Content rows -->
        <div
            v-for="i in rows"
            :key="i"
            class="grid px-5 py-3.5 gap-4 items-center border-b"
            style="grid-template-columns: 1fr 160px 80px 48px 112px; border-color: var(--border-subtle)"
            :style="{ opacity: 1 - (i - 1) * 0.07 }"
        >
            <!-- Avatar + name + company -->
            <div class="flex items-center gap-3">
                <div class="skeleton w-8 h-8 rounded-full shrink-0" />
                <div class="flex flex-col gap-1.5 flex-1">
                    <div class="skeleton h-3 rounded" :style="{ width: `${88 + (i % 4) * 24}px` }" />
                    <div class="skeleton h-2.5 rounded" :style="{ width: `${56 + (i % 3) * 18}px` }" />
                </div>
            </div>

            <!-- Email -->
            <div
                class="skeleton h-2.5 rounded hidden md:block"
                :style="{ width: `${100 + (i % 4) * 20}px` }"
            />

            <!-- Status badge -->
            <div class="skeleton h-5 rounded-full w-16" />

            <!-- Contacts count -->
            <div class="skeleton h-5 rounded-full w-7 mx-auto hidden sm:block" />

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-1">
                <div class="skeleton h-6 rounded-lg w-8" />
                <div class="skeleton h-6 rounded-lg w-12" />
                <div class="skeleton h-6 rounded-lg w-14" />
            </div>
        </div>
    </div>
</template>
