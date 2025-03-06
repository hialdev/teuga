<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransportInvoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'transport_invoices';
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
        $type = 'INV-LOG'; // Logistic Transport
        $lastRecord = self::whereYear('created_at', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function getProductQtyPriceAttribute()
    {
        $total_price = (int) $this->transport->total_price;
        $tax = (int) $this->transport->tax;
        $total_price_taxed = (int) $this->transport->total_price_taxed;

        return [
            'total_price' => $total_price,
            'tax' => $tax,
            'total_price_taxed' => $total_price_taxed,
        ];
    }

    public function getRemainingPaymentAttribute()
    {
        $totalPaid = $this->payments()->sum('paid_total'); 
        $totalPriceTaxed = $this->transport?->total_price_taxed ?? 0;

        return max(0, round($totalPriceTaxed - $totalPaid)); // Pastikan tidak negatif
    }

    public function getPaymentPercentageAttribute()
    {
        $paid = $this->payments()->sum('paid_total');
        $percentage = round(( $paid / $this->product_qty_price['total_price_taxed']) * 100, 2);
        return $percentage;
    }

    public function getSumPaidTotalAttribute()
    {
        return $this->payments()->sum('paid_total');
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class, 'transport_id');
    }

    public function payments(){
        return $this->hasMany(TransportPayment::class, 'transport_invoice_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'tp_invoice_transactions', 'tp_invoice_id', 'transaction_id')
            ->withTimestamps();
    }

    public function pay_transactions()
    {
        return $this->belongsToMany(Transaction::class, 'tp_payment_transactions', 'tp_payment_id', 'transaction_id')
            ->withTimestamps();
    }
}
