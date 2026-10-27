<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->timestamps();
        });

        Schema::create('navigation_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('navigation_menus')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_menu_items')->cascadeOnDelete();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('type', 16);
            $table->string('label', 160);
            $table->string('url', 2048)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
            $table->index(['menu_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_menu_items');
        Schema::dropIfExists('navigation_menus');
    }
};
