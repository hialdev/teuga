<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Account extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'accounting';
    protected $table = 'accounts';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            if($model->parent_account_id){
                $model->code = self::getChildCode($model->master_account_id, $model->parent_account_id);
            }else{
                $model->code = self::getCode($model->master_account_id);
            }
        });

        static::updating(function ($model) {
            // $model->full_code = $model->master->code . '.' . $model->code;
        });
    }

    public static function getCode($masterAccountId)
    {
        $lastRecord = self::where('master_account_id', $masterAccountId)
            ->orderBy('code', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval($lastRecord->code) : 99;
        $newNumber = $lastNumber + 1;

        return str_pad($newNumber, 3, '0', STR_PAD_LEFT); // Pastikan selalu 3 digit
    }

    public static function getChildCode($masterAccountId, $parentId)
    {
        $lastRecord = self::where('master_account_id', $masterAccountId)
            ->where('parent_account_id', $parentId)
            ->orderBy('code', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval($lastRecord->code) : 99;
        $newNumber = $lastNumber + 1;

        return str_pad($newNumber, 3, '0', STR_PAD_LEFT); // Pastikan selalu 3 digit
    }

    public function getFullCodeAttribute()
    {
        if($this->parent){
            return $this->master->code.'.'.$this->parent->code.'.'.$this->code;
        }else{
            return $this->master->code.'.'.$this->code;
        }
    }

    public function master()
    {
        return $this->belongsTo(MasterAccount::class, 'master_account_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_account_id');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionDetail::class, 'account_id');
    }

    public function getBalanceAttribute()
    {
        // Baca kategori akun (misal: aset, liabilitas, ekuitas, pendapatan, beban)
        $category = $this->master->type; 

        // Hitung saldo berdasarkan kategori akun
        switch ($category) {
            case 'assets':
            case 'expenses':
                return $this->transactions()->sum('debit') - $this->transactions()->sum('credit');
            
            case 'liabilities':
            case 'equity':
            case 'revenue':
                return $this->transactions()->sum('credit') - $this->transactions()->sum('debit');
            
            default:
                return 0;
        }
    }

}
