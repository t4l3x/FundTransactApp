<?php

namespace App\Models;

use App\ValueObjects\Currency;
use App\ValueObjects\Money;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class Account extends Model
{
    use HasFactory;
    use HasUuids;

//    protected $casts = [
//        'balance' => Money::class,
//    ];
    protected $casts = [
        'id' => 'string', // Use 'string' instead of 'uuid'
    ];
    public $keyType = 'string';

    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'currency', 'balance'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBalanceAttribute($value): Money
    {
        // Ensure $value is a valid numeric string
        if (!is_numeric($value)) {
            throw new InvalidArgumentException('Invalid numeric value for balance');
        }

        return Money::create((int) round($value * 100), new Currency($this->currency->getCurrency()));
    }

    public function getCurrencyAttribute($value): Currency
    {
        return new Currency($value);
    }
}
