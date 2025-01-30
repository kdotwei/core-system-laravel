<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function getFormData()
    {
        // Read schools CSV
        $schoolsCsv = storage_path('app/public/schools.csv');
        $schoolsRaw = array_map('str_getcsv', file($schoolsCsv));
        $schoolsData = array_slice($schoolsRaw, 1); // Skip header
        $schoolData = [];
        foreach ($schoolsData as $row) {
            $schoolData[] = [
                'id' => $row[0],   // school id
                'name' => $row[1], // school name
            ];
        }

        // Read department CSV
        $departmentsCsv = storage_path('app/public/110_sdata.csv');
        $departmentsRaw = array_map('str_getcsv', file($departmentsCsv));
        $departmentsData = array_slice($departmentsRaw, 1); // Skip header
        $departmentData = [];
        foreach ($departmentsData as $row) {
            $departmentData[] = [
                'school_id' => $row[0], // School ID
                'name' => $row[3], // Department name
                'degree' => $row[5], // Degree
            ];
        }

        return Inertia::render('Forms/ImformationForm', [
            'schools' => $schoolData,
            'departments' => $departmentData
        ]);
    }
}
