<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['checklist_group_id', 'name', 'user_id', 'checklist_id'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function userTasks()
    {
        return $this->hasMany(Task::class)->where('user_id', auth()->user()->id);
    }

    public function userCompletedTasks()
    {
        return $this->hasMany(Task::class)->where('user_id', auth()->user()->id)->whereNotNull('tasks.completed_at');
    }
}
