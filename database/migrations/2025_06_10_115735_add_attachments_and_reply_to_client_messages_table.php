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
        Schema::table('client_messages', function (Blueprint $table) {
            $table->enum('message_type', ['text', 'image', 'video', 'audio', 'file'])->default('text')->after('attachments');
            $table->foreignId('replied_by')->nullable()->constrained('users')->after('is_read');
            $table->text('staff_reply')->nullable()->after('replied_by');
            $table->json('reply_attachments')->nullable()->after('staff_reply');
            $table->timestamp('replied_at')->nullable()->after('reply_attachments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_messages', function (Blueprint $table) {
            $table->dropForeign(['replied_by']);
            $table->dropColumn(['message_type', 'replied_by', 'staff_reply', 'reply_attachments', 'replied_at']);
        });
    }
};
