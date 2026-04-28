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
    Schema::table('loans', function (Blueprint $table) {
        $table->integer('denda')->default(0);
        $table->boolean('is_paid')->default(false);
        $table->timestamp('paid_at')->nullable();
    });
}

public function down(): void
{
    Schema::table('loans', function (Blueprint $table) {
        $table->dropColumn(['denda', 'is_paid', 'paid_at']);
    });
}
};
