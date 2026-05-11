<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'specialty'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_teacher_group');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'module_teacher_group');
    }
}
