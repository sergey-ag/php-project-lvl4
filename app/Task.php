<?php

namespace Craftworks\TaskManager;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status_id',
        'creator_id',
        'assigned_to_id'
    ];

    public function status()
    {
        return $this->belongsTo(TaskStatus::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }

    public function getTagsAsString()
    {
        $tagNames = $this->tags->map(function ($tag, $key) {
            return $tag->name;
        })
            ->toArray();
        return implode(', ', $tagNames);
    }

    public static function getFiltered($filter)
    {
        return self::with('status', 'creator', 'assignedTo')
            ->when($filter['statusFilter'], function ($query, $filter) {
                return $query->whereIn('status_id', $filter);
            })
            ->when($filter['tagFilter'], function ($query, $filter) {
                return $query->whereHas('tags', function ($query) use ($filter) {
                    return $query->whereIn('tag_id', $filter);
                });
            })
            ->when($filter['userFilter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    return $query->whereIn('assigned_to_id', $filter)
                        ->when(in_array('null', $filter), function ($query) {
                            return $query->orWhereNull('assigned_to_id');
                        });
                });
            });
    }
}
