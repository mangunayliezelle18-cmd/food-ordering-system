<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('rider_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->string('delivery_proof_path')->nullable()->after('notes');
            $table->timestamp('delivered_at')->nullable()->after('delivery_proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['rider_id']);
            $table->dropColumn(['rider_id', 'delivery_proof_path', 'delivered_at']);
        });
    }
};
