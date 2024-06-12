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
        Schema::create('exit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('site_id')->constrained('sites');
            $table->foreignId('incoming_item_id')->unique()->constrained('incoming_items');
            $table->foreignId('status_part_id')->constrained('status_parts');
            $table->foreignId('status_exdismentie_id')->constrained('status_exdismenties');
            $table->string('image');
            $table->date('date_out_date');
            $table->string('nuisance_ticket');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_items');
    }
};
