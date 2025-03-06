<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PurchaseOrderInvoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'purchase_order_invoices';
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
        $type = 'INV-CPAL'; // Purchase Order Principal
        $lastRecord = self::whereYear('created_at', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function getProductQtyPriceAttribute()
    {
        $purchaseOrder = $this->purchaseOrder;

        $products = [];

        foreach ($purchaseOrder->products as $poProduct) {
            $productId = $poProduct->product_id;

            // Ambil RequestOrderProduct untuk mendapatkan price_buy

            $qty = $poProduct->qty;
            $price_buy = $poProduct->price_buy;

            $products[$productId] = [
                'qty' => $qty,
                'price_buy' => $price_buy,
                'total_price_buy' => $qty * $price_buy,
            ];
        }

        // Hitung total keseluruhan
        $total_price = array_sum(array_column($products, 'total_price_buy'));
        $tax = $purchaseOrder->tax;
        $total_price_taxed = $total_price + ($total_price * ($tax / 100));

        return [
            'products' => $products,
            'total_price' => $total_price,
            'tax' => $tax,
            'total_price_taxed' => $total_price_taxed,
        ];
    }

    public function getRemainingPaymentAttribute()
    {
        $totalPaid = $this->payments()->sum('paid_total'); 
        $totalPriceTaxed = $this->purchaseOrder?->total_price_taxed ?? 0;

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

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function payments(){
        return $this->hasMany(PurchaseOrderPayment::class, 'purchase_order_invoice_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'po_invoice_transactions', 'po_invoice_id', 'transaction_id')
            ->withTimestamps();
    }

    public function trx(){
        return $this->belongsTo(POInvoiceTransaction::class, 'po_invoice_id');
    }

    public function pay_transactions()
    {
        return $this->belongsToMany(Transaction::class, 'po_payment_transactions', 'po_payment_id', 'transaction_id')
            ->withTimestamps();
    }
}
