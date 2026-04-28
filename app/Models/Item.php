<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'stock', 'image'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}