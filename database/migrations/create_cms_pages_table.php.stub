<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('filament-static-pages.table_name', 'cms_pages');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();

            $table->string('titre');
            $table->string('slug')->unique();

            $table->json('meta_data')->nullable();
            $table->json('contents')->nullable();
            $table->json('statics')->nullable();

            $table->string('key_word')->nullable();

            $table->string('status')->default('draft');

            $table->boolean('is_homepage')->default(false);
            $table->boolean('is_in_header')->default(false);
            $table->boolean('is_in_footer')->default(false);
            $table->boolean('has_form')->default(false);

            $table->integer('order')->default(0);

            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('filament-static-pages.table_name', 'cms_pages'));
    }
};