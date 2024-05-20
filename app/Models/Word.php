<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereIn(...$args)
 * @method static create(array $array)
 */
class Word extends Model
{
    use HasFactory;

    const
        WORD = 'word',
        BRE = 'bre',
        AME = 'ame';

    protected $fillable = [
        self::WORD,
        self::BRE,
        self::AME,
    ];
}
