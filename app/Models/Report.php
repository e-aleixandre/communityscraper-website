<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string filename
 */
class Report extends Model
{
    use HasFactory;

    protected $fillable = ["min_date", "max_date", "token", "notify"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
