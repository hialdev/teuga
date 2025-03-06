<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'clients';
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
            $model?->addresses()?->delete();
            $model?->pics?->each->delete();
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
        });
    }

    public function addresses(){
        return $this->hasMany(ClientAddress::class, 'client_id')->orderBy('name', 'asc');
    }

    public function pics(){
        return $this->hasMany(ClientPic::class, 'client_id')->orderBy('name', 'asc');
    }
}
