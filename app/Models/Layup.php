<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layup extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'name'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function layers()
    {
        return $this->hasMany(Layer::class);
    }
}
