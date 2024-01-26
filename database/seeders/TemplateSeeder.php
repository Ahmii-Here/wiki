<?php

namespace Database\Seeders;

use BookStack\Users\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            ['name' => 'Default', 'path' => 'path/to/template1', 'isActive' => true],
            ['name' => 'Template 2', 'path' => 'path/to/template2', 'isActive' => true],
            ['name' => 'Template 3', 'path' => 'path/to/template3', 'isActive' => true],
        ];

        // Insert each template into the template table
        foreach ($templates as $templateData) {
            Template::create($templateData);
        }
    }
}
