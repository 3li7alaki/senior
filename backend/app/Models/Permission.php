<?php

namespace App\Models;


class Permission extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'title_ar',
        'name',
        'group',
        'group_ar',
    ];
}
