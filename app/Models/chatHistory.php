<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class chatHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'bot',
        'message'
    ];

       /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat_history';

    public function chat(): BelongsTo
    {
        return $this->belongsTo(chat::class, 'chat_id', 'id');
    }
}
