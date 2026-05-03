<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    protected $fillable = [
        'subscription_id',
        'invoice_id',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
