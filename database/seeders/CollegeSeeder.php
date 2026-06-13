<?php

namespace Database\Seeders;

use App\Models\College;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colleges = [
            [
                'name' => 'University of Mosul / جامعة الموصل',
                'latitude' => 36.383545,
                'longitude' => 43.140415,
            ],
            [
                'name' => 'Nineveh University / جامعة نينوى',
                'latitude' => 36.329718,
                'longitude' => 43.150946,
            ],
            [
                'name' => 'Northern Technical University / الجامعة التقنية الشمالية',
                'latitude' => 36.376760,
                'longitude' => 43.150204,
            ],
            [
                'name' => 'Al-Hadba University College / كلية الحدباء الجامعة',
                'latitude' => 36.335867,
                'longitude' => 43.181315,
            ],
            [
                'name' => 'Alnoor University / جامعة النور',
                'latitude' => 36.444548,
                'longitude' => 43.210703,
            ],
        ];

        // Loop through and insert into the database
        foreach ($colleges as $college) {
            College::create($college);
        }
    }
}
