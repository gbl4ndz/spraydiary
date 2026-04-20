<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $fillable = [
        'task_id', 'block_id', 'sheds', 'chemical_id', 'tank_capacity',
        'total_liquid', 'sprayed_by', 'is_fruiting', 'audit_check',
        'start_time', 'end_time', 'duration',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function chemical()
    {
        return $this->belongsTo(Chemical::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sprayed_by');
    }
}
