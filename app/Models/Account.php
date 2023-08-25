<?php

namespace App\Models;

use App\ValueObjects\Money;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
