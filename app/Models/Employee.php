<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\HasProfilePhoto;

class Employee extends Model
{
    use HasFactory;
    use HasProfilePhoto;

    protected $guarded = [];

    /**
     * Get all of the employee's dependents
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dependents()
    {
        return $this->hasMany(Dependent::class);
    }
}
