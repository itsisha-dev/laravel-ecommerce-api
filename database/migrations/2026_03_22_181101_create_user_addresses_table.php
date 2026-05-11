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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index();  // index for faster joins
            $table->string('fullname', 100);
            $table->enum('type', ['shipping', 'billing'])->default('shipping');
            $table->enum('label', ['Home', 'Work', 'Other'])->default('Home');
            $table->string('address');
            $table->string('city', 100)->index();
            $table->string('state', 100)->index();
            $table->string('country', 100)->index();
            $table->string('postal_code', 10);
            $table->string('phone', 10)->index();          
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
