<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'coefficient', 'total_hours'];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Sum of the duration of all published sessions for this module,
     * across all groups. Compared against total_hours to enforce the
     * module's annual allocation.
     */
    public function getUsedHoursAttribute(): float
    {
        return (float) $this->sessions()
            ->where('is_published', true)
            ->sum('duration_hours');
    }

    public function getRemainingHoursAttribute(): float
    {
        return max(((float) $this->total_hours) - $this->used_hours, 0.0);
    }
}
