<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'accounting';
    protected $table = 'transaction_details';
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
        static::deleting(function ($model) {
        });
    }

    public function getRunningBalance($initial, $entry)
    {
        $runningBalance = (float) ($initial ?? 0);
        $transaction = $entry;
        $type = $transaction->account->master->type;
        $debit = (float) $transaction->debit ?? 0;
        $credit = (float) $transaction->credit ?? 0;

        if (in_array($type, ['assets', 'expenses'])) {
            $runningBalance += ($debit - $credit);
        } else {
            $runningBalance += ($credit - $debit);
        }

        $newBalance = $runningBalance;
        return $newBalance;
    }


    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
