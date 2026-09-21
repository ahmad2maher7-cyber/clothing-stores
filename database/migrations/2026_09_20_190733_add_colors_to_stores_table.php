<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('primary_color')->default('#4A4A9D')->after('banner');
            $table->string('secondary_color')->default('#1E293B')->after('primary_color');
            $table->string('accent_color')->default('#EF4444')->after('secondary_color');
            $table->enum('theme_mode', ['light', 'dark'])->default('light')->after('accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'secondary_color', 'accent_color', 'theme_mode']);
        });
    }
};