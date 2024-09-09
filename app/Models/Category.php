<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $dates = ['deleted_at'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
