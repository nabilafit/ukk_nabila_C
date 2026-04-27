<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'nama_peminjam',
        'borrow_date',
        'due_date',
        'return_date',
        'status'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user() {
        return $this->belongsTo(Item::class);
    }
}
