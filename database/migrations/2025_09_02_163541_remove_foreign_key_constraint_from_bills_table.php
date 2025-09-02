<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère sur company_id
            $table->dropForeign(['company_id']);
            // Rendre company_id nullable
            $table->unsignedBigInteger('company_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            // Remettre la contrainte de clé étrangère
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
};
