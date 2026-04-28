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
        Schema::create('loans', function (Blueprint $table) {
             $table->id();
             $table->foreignId('user_id')->constrained()->cascadeOnDelete();
             $table->foreignId('item_id')->constrained()->cascadeOnDelete();

             $table->string('nama_peminjam');
             $table->integer('jumlah')->default(1);

             $table->date('borrow_date');
             $table->date('due_date')->nullable();
             $table->date('return_date')->nullable();

             $table->enum('status', ['dipinjam', 'kembali', 'hilang', 'rusak'])->default('dipinjam');
 
     // PAYMENT SYSTEM
             $table->integer('denda')->default(0);
             $table->boolean('is_paid')->default(false);
             $table->timestamp('paid_at')->nullable();

            $table->timestamps();
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
