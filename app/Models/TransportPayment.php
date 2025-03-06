<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransportPayment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'transport_payments';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->code = self::getCode();
        });
        static::deleting(function ($model) {
        });
    }

    protected static function getCode()
    {
        $type = 'PAID-LOG'; // Logistic Transport
        $lastRecord = self::whereYear('created_at', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function invoice(){
        return $this->belongsTo(TransportInvoice::class, 'transport_invoice_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'tp_payment_transactions', 'tp_payment_id', 'transaction_id')
            ->withTimestamps();
    }
}
