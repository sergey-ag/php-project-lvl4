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
        return $this->belongsTo(TaskStatus::class)->withTrashed();
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class)->withTrashed();
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

    public static function getFilteredMultiple($filter)
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
                    return $query->whereIn('assigned_to_id', array_filter($filter, function ($item) {
                        return $item !== 'null';
                    }))
                        ->when(in_array('null', $filter), function ($query) {
                            return $query->orWhereNull('assigned_to_id');
                        });
                });
            });
    }

    public static function getFiltered($filter)
    {
        return self::with('status', 'creator', 'assignedTo')
            ->when($filter['statusFilter'], function ($query, $filter) {
                return $query->where('status_id', $filter);
            })
            ->when($filter['tagFilter'], function ($query, $filter) {
                return $query->whereHas('tags', function ($query) use ($filter) {
                    return $query->where('tag_id', $filter);
                });
            })
            ->when($filter['userFilter'], function ($query, $filter) {
                return $query->when($filter !== 'null', function ($query) use ($filter) {
                    return $query->where('assigned_to_id', $filter);
                }, function ($query) {
                    return $query->whereNull('assigned_to_id');
                });
            });
    }
}
