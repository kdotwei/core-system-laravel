<script setup>
import { ref, computed } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectInput from "@/Components/SelectInput.vue";
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
                <SelectInput
                    v-model="selectedSchool"
                    label="name"
                    placeholder="Search or select a school"
                    :options="schools"
                />
            </div>

            <!-- Department Selection -->
            <div>
                <InputLabel for="departmen" value="Select Department" />
                <SelectInput
                    v-model="selectedDepartment"
                    label="fullName"
                    placeholder="Search or select a department"
                    :options="filteredDepartments"
                    :disabled="!selectedSchool"
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
