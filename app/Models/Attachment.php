<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Attachment extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'id',
        'filename',
        'path',
        'mime_type',
        'extension',
        'group',
    ];

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'attachable')
            ->using(Attachable::class)
            ->withTimestamps();
    }
}
