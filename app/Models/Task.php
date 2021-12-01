<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'task_details',
        'status',
        'deadline'
    ];

    public function project(){
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
