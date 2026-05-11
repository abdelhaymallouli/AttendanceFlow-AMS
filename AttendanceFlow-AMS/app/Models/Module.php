<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'coefficient'];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
