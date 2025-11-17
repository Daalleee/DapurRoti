<?php

namespace App\Models;

use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'status',
        'notes',
        'address',
        'payment_method',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        });
        
        // Listen for status changes to send notifications
        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                $oldStatus = $order->getOriginal('status');
                
                // Send notification to the order owner
                $order->user->notify(new OrderStatusChangedNotification($order, $oldStatus));
                
                // Send notification to admin users
                $adminUsers = User::where('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    $admin->notify(new OrderStatusChangedNotification($order, $oldStatus));
                }
            }
        });
    }
}
