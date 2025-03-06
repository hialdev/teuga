<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PurchaseOrderPayment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'purchase_order_payments';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->code = (string) self::getCode();
        });
        static::deleting(function ($model) {
            if ($model->file) {
                Storage::disk('public')->delete($model->file);
            }
        });
    }

    protected static function getCode()
    {
        $type = 'PAID-CPAL'; // Purchase Order Principal
        $lastRecord = self::whereYear('created_at', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function invoice(){
        return $this->belongsTo(PurchaseOrderInvoice::class, 'purchase_order_invoice_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'po_payment_transactions', 'po_payment_id', 'transaction_id')
            ->withTimestamps();
    }
}
