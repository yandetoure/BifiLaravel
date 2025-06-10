<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'wizall_start_balance',
        'wizall_current_balance',
        'wizall_final_balance',
        'wave_start_balance',
        'wave_final_balance',
        'orange_money_balance',
        'cash_balance',
        'total_to_return',
    ];

    protected $casts = [
        'date' => 'date',
        'wizall_start_balance' => 'decimal:2',
        'wizall_current_balance' => 'decimal:2',
        'wizall_final_balance' => 'decimal:2',
        'wave_start_balance' => 'decimal:2',
        'wave_final_balance' => 'decimal:2',
        'orange_money_balance' => 'decimal:2',
        'cash_balance' => 'decimal:2',
        'total_to_return' => 'decimal:2',
    ];

    public static function getTodayBalance()
    {
        return self::whereDate('date', today())->first();
    }

    public static function getYesterdayBalance()
    {
        return self::whereDate('date', today()->subDay())->first();
    }
}
