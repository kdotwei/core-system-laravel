<script setup>
import { defineProps, defineEmits } from "vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

const props = defineProps({
    modelValue: [Object, Array, String, Number, null], // Bound value
    options: Array, // Dropdown options
    label: String, // Key for displaying option names
    placeholder: String, // Placeholder text
    disabled: Boolean, // Whether the input is disabled
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <v-select
        @update:modelValue="emit('update:modelValue', $event)"
        :modelValue="modelValue"
        :options="options"
        :label="label"
        :placeholder="placeholder"
        append-to-body
        class="text-white rounded-md
                        dark:bg-gray-300 dark:active:bg-gray-300 dark:focus:bg-white 
                        dark:text-gray-800 dark:focus:ring-offset-gray-800 dark:hover:bg-white 
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:bg-gray-700
                        hover:bg-gray-700 active:bg-gray-900"
    />
</template>

<style>
.vs__search::placeholder {
    @apply text-gray-400;
}
</style>