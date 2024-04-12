<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'id',
        'product_model',
        'product_type',
        'police_number',
        'product_price',
        'is_active',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Attachment::class, 'attachable')
            ->using(Attachable::class)
            ->withTimestamps();
    }
}
