<?php

use BookStack\Users\Models\Template;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create template table
        Schema::create('template', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('path');
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });

        // update roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->nullable();
            $table->foreign('template_id')
                ->references('id')
                ->on('template')
                ->onDelete('set null');
        });
        $templates = [
            ['name' => 'Default', 'path' => 'home.templates.default', 'isActive' => true],
            ['name' => 'Template 2', 'path' => 'home.templates.template_2', 'isActive' => true],
            ['name' => 'Template 3', 'path' => 'home.templates.template_3', 'isActive' => true],
        ];

        // Insert each template into the template table
        foreach ($templates as $templateData) {
            Template::create($templateData);
        }
        DB::table('roles')->update(['template_id' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback for updating the 'roles' table
        Schema::table('roles', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['template_id']);

            // Remove the added column
            $table->dropColumn('template_id');
        });

        // Rollback for creating the 'template' table
        Schema::dropIfExists('template');


    }
};
