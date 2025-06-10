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
            // Ajouter client_name si elle n'existe pas
            if (!Schema::hasColumn('payments', 'client_name')) {
                $table->string('client_name')->nullable()->after('agent_id');
            }
            
            // Ajouter les colonnes de calcul de change si elles n'existent pas
            if (!Schema::hasColumn('payments', 'amount_received')) {
                $table->decimal('amount_received', 15, 2)->nullable()->after('total');
            }
            
            if (!Schema::hasColumn('payments', 'change_amount')) {
                $table->decimal('change_amount', 15, 2)->default(0)->after('amount_received');
            }
            
            if (!Schema::hasColumn('payments', 'change_method')) {
                $table->string('change_method')->nullable()->after('change_amount');
            }
            
            // Ajouter la colonne status si elle n'existe pas
            if (!Schema::hasColumn('payments', 'status')) {
                $table->enum('status', ['pending', 'completed', 'failed'])->default('pending')->after('change_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Supprimer les colonnes seulement si elles existent
            $columnsToRemove = ['client_name', 'amount_received', 'change_amount', 'change_method', 'status'];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
