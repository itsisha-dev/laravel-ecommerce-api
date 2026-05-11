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
        // Modify users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 25)->default('customer')->index();
            $table->boolean('is_active')->default(true);
            $table->softDeletes(); // Adds a nullable 'deleted_at' column
            $table->dropColumn('remember_token'); // remove field
        });

        // Drop sessions table
        Schema::dropIfExists('sessions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
