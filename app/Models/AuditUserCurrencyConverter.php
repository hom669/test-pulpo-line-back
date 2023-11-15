<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuditUserCurrencyConverter extends Model
{
    protected $table = 'audit_user_currency_converter';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        // Otros campos si los tienes
    ];

    protected static function boot()
    {
        parent::boot();

        // Se ejecuta antes de que se cree un nuevo registro
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
