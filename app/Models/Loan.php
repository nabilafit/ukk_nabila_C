<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'nama_peminjam',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'jumlah',
        'denda',
        'is_paid',
        'paid_at'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // FINAL DENDA LOGIC
    public function getFinalDendaAttribute()
    {
        if ($this->is_paid) return 0;

        if ($this->status == 'rusak') {
            return 50000 * $this->jumlah;
        }

        if ($this->status == 'hilang') {
            return 100000 * $this->jumlah;
        }

        if ($this->status == 'dipinjam' && $this->due_date) {
            if (Carbon::parse($this->due_date)->isPast()) {
                $hariTelat = now()->diffInDays(Carbon::parse($this->due_date));
                return $hariTelat * 5000 * $this->jumlah;
            }
        }

        return 0;
    }
}