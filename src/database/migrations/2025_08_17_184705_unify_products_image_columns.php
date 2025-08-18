<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('products', 'img_url')) {
            DB::statement('UPDATE products SET image_path = COALESCE(image_path, img_url)');
            // img_url を削除
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('img_url');
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'img_url')) {
                $table->string('img_url')->nullable()->after('image_path');
            }
        });
    }
};
