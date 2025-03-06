<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'purchase_orders';
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
            $model?->transport?->delete();
            $model?->products?->each->delete();
            $model?->files?->each->delete();
        });
    }

    protected static function getCode()
    {
        $type = 'PO-CPAL'; // Purchase Order Principal
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public static function totalProcessed()
    {
        $purchases = self::whereHas('invoice')->where('status', '!=', '0')->get();
        $totalProcessed = 0;
        foreach ($purchases as $purchase) {
            $totalProcessed += $purchase->invoice->sum_paid_total;
        }   
        return round($totalProcessed);
    }

    public static function remainingProcessed(){
        $allTotal = self::all()->sum('total_price_taxed');
        return round($allTotal - self::totalProcessed());
    }

    public function requestOrder(){
        return $this->belongsTo(RequestOrder::class, 'request_order_id');
    }

    public function products(){
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id')
                    ->join('products', 'purchase_order_products.product_id', '=', 'products.id')
                    ->orderBy('products.name', 'asc')
                    ->select('purchase_order_products.*'); // Hindari konflik kolom
    }

    public function files(){
        return $this->hasMany(PurchaseOrderFile::class, 'purchase_order_id')->orderBy('name', 'asc');
    }

    public function principal(){
        return $this->belongsTo(Principal::class, 'principal_id');
    }

    public function pic(){
        return $this->belongsTo(PrincipalPic::class, 'principal_pic_id');
    }

    public function transport(){
        return $this->belongsTo(Transport::class, 'transport_id');
    }

    public function pickup(){
        return $this->belongsTo(PrincipalAddress::class, 'pickup_address_id');
    }
    
    public function delivery(){
        return $this->belongsTo(ClientAddress::class, 'delivery_address_id');
    }

    public function invoice(){
        return $this->hasOne(PurchaseOrderInvoice::class, 'purchase_order_id');
    }

    public function clientInvoice(){
        return $this->hasOne(RequestOrderInvoice::class, 'purchase_order_id');
    }
}
