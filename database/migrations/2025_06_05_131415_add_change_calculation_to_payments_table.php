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
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount_received', 15, 2)->nullable()->after('total');
            $table->decimal('change_amount', 15, 2)->default(0)->after('amount_received');
            $table->string('change_method')->nullable()->after('change_amount'); // wave, om, cash
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending')->after('change_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['amount_received', 'change_amount', 'change_method', 'status']);
        });
    }
};
