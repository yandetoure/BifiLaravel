<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'receipt_number',
        'client_name',
        'file_path',
        'sent_by_email',
        'sent_by_whatsapp',
    ];

    protected $casts = [
        'sent_by_email' => 'boolean',
        'sent_by_whatsapp' => 'boolean',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
