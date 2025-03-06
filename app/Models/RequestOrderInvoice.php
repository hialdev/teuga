<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestOrderInvoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'request_order_invoices';
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
        $type = 'INV-CL'; 
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
        $requestOrder = $this->requestOrder;
        $products = [];

        if($purchaseOrder){
            foreach ($purchaseOrder->products as $poProduct) {
                $productId = $poProduct->product_id;

                // Ambil RequestOrderProduct untuk mendapatkan price_buy
                $roProduct = $requestOrder->products->where('product_id', $productId)->first();

                $qty = $poProduct->qty;
                $price_buy = $roProduct ? $poProduct->price_buy : 0; // pastikan ada data, jika tidak 0
                $price_sale = $roProduct->price_sale;

                $products[$productId] = [
                    'qty' => $qty,
                    'price_buy' => $price_buy,
                    'price_sale' => $price_sale,
                    'total_price_buy' => $qty * $price_buy,
                    'total_price_sale' => $qty * $price_sale,
                ];
            }

            // Hitung total keseluruhan
            $total_price = array_sum(array_column($products, 'total_price_sale'));
            $tax = $requestOrder->tax;
            $total_price_taxed = $total_price + ($total_price * ($tax / 100));
        }else{
            foreach ($requestOrder->products as $roProduct) {
                $productId = $roProduct->product_id;

                $qty = $roProduct->qty;
                $price_buy = $roProduct ? $roProduct->price_buy : 0; // pastikan ada data, jika tidak 0
                $price_sale = $roProduct->price_sale;

                $products[$productId] = [
                    'qty' => $qty,
                    'price_buy' => $price_buy,
                    'price_sale' => $price_sale,
                    'total_price_buy' => $qty * $price_buy,
                    'total_price_sale' => $qty * $price_sale,
                ];
            }

            // Hitung total keseluruhan
            $total_price = array_sum(array_column($products, 'total_price_sale'));
            $tax = $requestOrder->tax;
            $total_price_taxed = $total_price + ($total_price * ($tax / 100));
        }

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
        $totalPriceTaxed = $this->product_qty_price['total_price_taxed'] ?? 0;

        return max(0, round($totalPriceTaxed - $totalPaid)); // Pastikan tidak negatif
    }

    public function getPaymentPercentageAttribute()
    {
        $totalPaid = $this->payments()->sum('paid_total'); 
        $percentage = round(($totalPaid / $this->product_qty_price['total_price_taxed']) * 100, 2);
        return $percentage;
    }

    public function getPaymentPercentageSecAttribute()
    {
        $totalPaid = $this->payments()->sum('paid_total'); 
        $percentage = round(($totalPaid / $this->requestOrder->total_price_taxed) * 100, 2);
        return $percentage;
    }
    
    public function getSumPaidTotalAttribute()
    {
        return $this->payments()->sum('paid_total');
    }

    public function purchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function requestOrder(){
        return $this->belongsTo(RequestOrder::class, 'request_order_id');
    }

    public function payments(){
        return $this->hasMany(RequestOrderPayment::class, 'request_order_invoice_id');
    }

    public function trx(){
        return $this->hasMany(ROInvoiceTransaction::class, 'ro_invoice_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'ro_invoice_transactions', 'ro_invoice_id', 'transaction_id')
            ->withTimestamps();
    }
}
