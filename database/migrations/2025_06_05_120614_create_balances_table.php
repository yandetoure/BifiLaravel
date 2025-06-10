<?php declare(strict_types=1); 

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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('wizall_start_balance', 15, 2)->default(0);
            $table->decimal('wizall_current_balance', 15, 2)->default(0);
            $table->decimal('wizall_final_balance', 15, 2)->default(0);
            $table->decimal('wave_start_balance', 15, 2)->default(0);
            $table->decimal('wave_final_balance', 15, 2)->default(0);
            $table->decimal('orange_money_balance', 15, 2)->default(0);
            $table->decimal('cash_balance', 15, 2)->default(0);
            $table->decimal('total_to_return', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
