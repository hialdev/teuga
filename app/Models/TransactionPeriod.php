<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransactionPeriod extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'accounting';
    protected $table = 'transaction_periods';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
        static::deleting(function ($model) {
        });
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'transaction_period_id');
    }
}
