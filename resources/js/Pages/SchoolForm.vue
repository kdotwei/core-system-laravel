<script setup>
import { ref, computed } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue"; // Jetstream default layout
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

const page = usePage();
const schools = ref(page.props.schools);
const departments = ref(page.props.departments);

const selectedSchool = ref(null);
const selectedDepartment = ref(null);

// Filter departments based on the selected school
const filteredDepartments = computed(() => {
  if (!selectedSchool.value) return [];
  return departments.value
    .filter((dept) => dept.school_id == selectedSchool.value.id)
    .map((dept) => ({
      ...dept,
      fullName: `${dept.name} (${dept.degree})`, // Display as "Department Name (Degree)"
    }));
});

// Submit form
const submitForm = () => {
  console.log("Selected School:", selectedSchool.value);
  console.log("Selected Department:", selectedDepartment.value);
};
</script>

<template>
    <Head title="School & Department Selection" />
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>
        
        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- School Selection -->
            <div>
                <InputLabel for="school" value="Select School" />
                <v-select 
                    v-model="selectedSchool"
                    label="name"
                    :options="schools"
                    placeholder="Search or select a school"
                    class="text-white rounded-md
                        dark:bg-gray-200 dark:active:bg-gray-300 dark:focus:bg-white 
                        dark:text-gray-800 dark:focus:ring-offset-gray-800 dark:hover:bg-white 
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:bg-gray-700
                        hover:bg-gray-700 active:bg-gray-900"
                />
            </div>

            <!-- Department Selection -->
            <div>
                <InputLabel for="departmen" value="Select Department" />
                <v-select
                    v-model="selectedDepartment"
                    label="fullName"
                    :options="filteredDepartments"
                    placeholder="Search or select a department"
                    :disabled="!selectedSchool"
                    class="text-white rounded-md
                        dark:bg-gray-200 dark:active:bg-gray-300 dark:focus:bg-white 
                        dark:text-gray-800 dark:focus:ring-offset-gray-800 dark:hover:bg-white 
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:bg-gray-700
                        hover:bg-gray-700 active:bg-gray-900"
                />
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ms-4">
                    Submit
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
