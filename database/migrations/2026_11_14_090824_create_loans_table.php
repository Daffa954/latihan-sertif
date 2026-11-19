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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            // FK Section starts here
            $table->foreignId('borrower_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->foreignId('approver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            // FK Section ends here
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('borrower_name');
            $table->string('approver_name')->nullable();
            $table->timestamp("borrowed_at")->nullable();
            $table->timestamp("returned_at")->nullable();
            $table->timestamp('due_date')->nullable();
            $table->decimal('fine_amount', 12, 2)->default(0);
             $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
