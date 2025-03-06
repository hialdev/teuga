<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'accounting';
    protected $table = 'transactions';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->code = (string) self::getCode($model->date);
        });
        static::deleting(function ($model) {
            $model->po_invoice?->delete();
            $model->ro_invoice?->delete();
            $model->tp_invoice?->delete();
        });
    }

    protected static function getCode($date)
    {
        $type = 'TRX'; // Transaction
        $month = (int) \Carbon\Carbon::parse($date)->format('m');
        $year = (int) \Carbon\Carbon::parse($date)->format('Y');
        $lastRecord = self::whereYear('date', '=', $year)
            ->orderBy('code', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;
        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber, null, $month, $year); // Fungsi generateCode dengan nilai default
    }

    public function details(){
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function po_invoice()
    {
        return $this->hasOne(POInvoiceTransaction::class, 'transaction_id');
    }

    public function ro_invoice()
    {
        return $this->hasOne(ROInvoiceTransaction::class, 'transaction_id');
    }

    public function tp_invoice()
    {
        return $this->hasOne(TransportInvoiceTransaction::class, 'transaction_id');
    }

    public function period(){
        return $this->belongsTo(TransactionPeriod::class, 'transaction_period_id');
    }
}
