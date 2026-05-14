<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'send_at',
        'status',
        'total_recipients',
        'sent_count',
        'created_by',
        'scheduled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime', // Giúp tự động parse thành object Carbon
    ];


    /**
     * Quan hệ n-n với Subscriber thông qua bảng recipients
     */
    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class, 'campaign_recipients')
            -> withPivot('status', 'error_message')
            -> withTimestamps();
    }
}
