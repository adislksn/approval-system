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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('uuid')->unique();
            $table->string('nopol')->nullable();
            $table->integer('tahun')->nullable();
            $table->enum('merk', ['Izuzu', 'Daihatsu', 'TATA', 'Mitsubishi', 'Hino',  'none'])->default('none');
            $table->enum('shipping_type', ['4A', '4B', '4G', '4R', 'GD', '6R', '6H', 'WR', 'none'])->default('none');
            $table->float('kilometer')->nullable();
            $table->json('description_service')->nullable();
            $table->text('service_location')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['pending', 'accepted 1', 'accepted 2', 'done', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
