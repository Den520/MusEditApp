<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_published',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accessedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sheet_users', 'sheet_id', 'user_id');
    }

    public function isUserHasAccess($user)
    {
        return SheetUser::where('sheet_id', $this->id)->where('user_id', $user->id)->exists();
    }
}
