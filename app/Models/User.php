<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'position_id', 'photo'];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

}
