<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'application_id',
        'status',
        'transfer_price'
    ];

    protected $casts = [
        'transfer_price' => 'decimal:2'
    ];

    // relationship
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function histories()
    {
        return $this->hasMany(AccountHistory::class);
    }
}
