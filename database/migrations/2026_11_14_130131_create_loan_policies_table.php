<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_policies', function (Blueprint $table) {
            $table->id();
            $table->enum('grade', ['bachelor', 'magister', 'lecturer', 'staff', 'head'])->default('bachelor')->unique();
            $table->integer('max_borrowed_books')->default(3); 
            $table->integer('max_borrow_duration')->default(7); //in days
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_policies');
    }
};
