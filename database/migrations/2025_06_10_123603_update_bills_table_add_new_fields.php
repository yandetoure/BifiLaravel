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
        Schema::table('bills', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('client_number');
            $table->string('client_name')->nullable()->after('client_number');
            $table->string('company_name')->nullable()->after('company_id');
            $table->string('invoice_number')->nullable()->after('bill_number');
            $table->text('description')->nullable()->after('amount');
            $table->date('due_date')->nullable()->after('amount');
            $table->string('facturier')->nullable()->after('company_id');
            $table->unsignedBigInteger('client_user_id')->nullable()->after('user_id');
            $table->boolean('is_third_party_payment')->default(false)->after('amount');
            $table->unsignedBigInteger('paid_by_user_id')->nullable()->after('user_id');
            $table->datetime('paid_at')->nullable()->after('status');
            $table->string('transaction_reference')->nullable()->after('status');
            $table->string('payment_method')->nullable()->after('status');
            $table->text('notes')->nullable()->after('status');
            
            // Index pour les recherches
            $table->index(['client_user_id']);
            $table->index(['paid_by_user_id']);
            $table->index(['phone']);
            $table->index(['facturier']);
            
            // Clés étrangères
            $table->foreign('client_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('paid_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropForeign(['client_user_id']);
            $table->dropForeign(['paid_by_user_id']);
            $table->dropIndex(['client_user_id']);
            $table->dropIndex(['paid_by_user_id']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['facturier']);
            $table->dropColumn([
                'phone', 'client_name', 'company_name', 'invoice_number', 
                'description', 'due_date', 'facturier', 'client_user_id', 
                'is_third_party_payment', 'paid_by_user_id', 'paid_at', 
                'transaction_reference', 'payment_method', 'notes'
            ]);
        });
    }
};
