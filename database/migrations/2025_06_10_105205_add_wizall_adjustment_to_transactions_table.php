<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter wizall_adjustment et manual_adjustment au type enum
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM(
            'deposit', 
            'transfer', 
            'withdrawal', 
            'supervisor_deposit',
            'bank_deposit_agent',
            'bank_deposit_supervisor',
            'wizall_adjustment',
            'manual_adjustment'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'ancienne définition
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM(
            'deposit', 
            'transfer', 
            'withdrawal', 
            'supervisor_deposit',
            'bank_deposit_agent',
            'bank_deposit_supervisor'
        ) NOT NULL");
    }
};
