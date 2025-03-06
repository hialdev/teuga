<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Asset extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'accounting';
    protected $table = 'assets';
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
    }

    public function calculateDepreciation($purchase_price, $residu_price, $useful_life, $years_passed) {
        if ($this->is_appreciating) return null;
        if ($useful_life == 0 ) return 0; // Hindari pembagian dengan nol

        $annual_depreciation = ($purchase_price - $residu_price) / $useful_life;
        $depreciation_accumulated = $annual_depreciation * $years_passed;

        // Nilai aset setelah penyusutan
        $current_value = max($purchase_price - $depreciation_accumulated, $residu_price);
        
        return [
            'annual_depreciation' => $annual_depreciation,
            'depreciation_accumulated' => $depreciation_accumulated,
            'current_value' => $current_value,
        ];
    }

    public function calculateAppreciation($current_value, $appreciation_rate, $years_passed) {
        if (!$this->is_appreciating) return null;
        if ($appreciation_rate <= 0) return $current_value;

        $new_value = $current_value * pow((1 + $appreciation_rate / 100), $years_passed);
        
        return $new_value;
    }

    public function account(){
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function transaction(){
        return $this->hasOne(TransactionDetail::class, 'account_id', 'account_id');
    }
}
