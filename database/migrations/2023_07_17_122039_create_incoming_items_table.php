<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incoming_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites');
            $table->foreignId('type_part_id')->constrained('type_parts');
            $table->foreignId('item_part_id')->constrained('item_parts');
            $table->foreignId('status_part_id')->constrained('status_parts');
            $table->foreignId('status_exdismentie_id')->constrained('status_exdismenties');

            $table->string('image');
            $table->date('date_entry');
            $table->string('nuisance_ticket');
            $table->text('description')->nullable();
            $table->string('part_number')->unique();
            $table->string('serial_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_items');
    }
};
