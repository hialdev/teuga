<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transport extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'transports';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->code = static::getCode();
            $model->spk_code = static::getSPKCode();
            $model->surjal_code = static::getSJCode();
        });
        static::deleting(function ($model) {
        });
    }

    protected static function getCode()
    {
        $type = 'PO-LOG'; // Purchase Order Client
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    protected static function getSPKCode()
    {
        $type = 'SPK'; // Purchase Order Client
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    protected static function getSJCode()
    {
        $type = 'SJ'; // Purchase Order Client
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public static function totalProcessed()
    {
        $transports = self::whereHas('invoice')->where('status', '!=', '0')->get();
        $totalProcessed = 0;
        foreach ($transports as $transport) {
            $totalProcessed += $transport->invoice->sum_paid_total;
        }   
        return round($totalProcessed);
    }

    public static function remainingProcessed(){
        $allTotal = self::all()->sum('total_price_taxed');
        return round($allTotal - self::totalProcessed());
    }

    public function logistic(){
        return $this->belongsTo(Logistic::class, 'logistic_id');
    }

    public function purchaseOrder(){
        return $this->hasOne(PurchaseOrder::class, 'transport_id');
    }

    public function invoice(){
        return $this->hasOne(TransportInvoice::class, 'transport_id');
    }
    
}
