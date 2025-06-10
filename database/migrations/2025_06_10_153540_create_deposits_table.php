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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('deposit_date');
            $table->decimal('amount', 15, 2);
            $table->enum('deposit_type', ['agent_cash_deposit', 'supervisor_wizall_deposit', 'cash_collection', 'wizall_refill']);
            $table->enum('source', ['cash', 'wizall', 'wave', 'orange_money']);
            $table->enum('destination', ['cash', 'wizall', 'wave', 'orange_money']);
            $table->text('description')->nullable();
            $table->decimal('balance_before', 15, 2)->nullable();
            $table->decimal('balance_after', 15, 2)->nullable();
            $table->decimal('cash_balance_before', 15, 2)->nullable();
            $table->decimal('cash_balance_after', 15, 2)->nullable();
            $table->decimal('wizall_balance_before', 15, 2)->nullable();
            $table->decimal('wizall_balance_after', 15, 2)->nullable();
            $table->boolean('affects_agent_return')->default(false); // Pour savoir si ça impacte ce que l'agent doit rendre
            $table->decimal('agent_return_amount', 15, 2)->default(0); // Montant que l'agent doit rendre après ce versement
            $table->json('transaction_details')->nullable(); // Détails supplémentaires
            $table->timestamps();
            
            // Index pour les requêtes fréquentes
            $table->index(['deposit_date', 'deposit_type']);
            $table->index(['user_id', 'deposit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
