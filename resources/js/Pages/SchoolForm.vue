<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue"; // Jetstream default layout
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
  <AppLayout title="School & Department Selection">
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">
            School & Department Selection
        </h2>
        
        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- School Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Select School</label>
                <v-select 
                    v-model="selectedSchool"
                    label="name"
                    :options="schools"
                    placeholder="Search or select a school"
                    class="mt-1 block w-full"
                />
            </div>

            <!-- Department Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Select Department</label>
                <v-select
                v-model="selectedDepartment"
                label="fullName"
                :options="filteredDepartments"
                placeholder="Search or select a department"
                :disabled="!selectedSchool"
                class="mt-1 block w-full"
                />
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button 
                type="submit" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow"
                >
                Submit
                </button>
            </div>
        </form>
    </div>
  </AppLayout>
</template>
