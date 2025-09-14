<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $primaryKey = 'skill_id';
    public $timestamps = false; // <-- Add this line

    protected $fillable = ['name', 'category'];
    
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'skill_id', 'skill_id');
    }

    public function tradesOffering()
    {
        return $this->hasMany(\App\Models\Trade::class, 'offering_skill_id', 'skill_id');
    }

    public function tradesLooking()
    {
        return $this->hasMany(\App\Models\Trade::class, 'looking_skill_id', 'skill_id');
    }
}
