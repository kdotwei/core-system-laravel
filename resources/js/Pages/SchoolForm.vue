<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
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
      fullName: `${dept.name} (${dept.degree})`, // Display as "Department Name (Drgree)"
    }));
});

// Submit form
const submitForm = () => {
  console.log("Selected School:", selectedSchool.value);
  console.log("Selected Department:", selectedDepartment.value);
};
</script>

<template>
  <div class="container">
    <h2>School & Department Selection Form</h2>
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label>Select School:</label>
        <v-select 
          v-model="selectedSchool"
          label="name"
          :options="schools"
          placeholder="Search or select a school"
        />
      </div>

      <div class="form-group">
        <label>Select Department:</label>
        <v-select
          v-model="selectedDepartment"
          label="fullName"
          :options="filteredDepartments"
          placeholder="Search or select a department"
          :disabled="!selectedSchool"
        />
      </div>

      <button type="submit">Submit</button>
    </form>
  </div>
</template>

<style>
.container {
  max-width: 500px;
  margin: auto;
}
.form-group {
  margin-bottom: 15px;
}
</style>
