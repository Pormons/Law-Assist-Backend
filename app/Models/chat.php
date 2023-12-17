<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class chat extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'title',
    ];

       /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat';

    public function chatHistory(): HasMany
    {
        return $this->hasMany(chatHistory::class, 'chat_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
