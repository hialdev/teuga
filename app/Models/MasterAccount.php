<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MasterAccount extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'accounting';
    protected $table = 'master_accounts';
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

    public function accounts(){
        return $this->hasMany(Account::class, 'master_account_id');
    }
}
