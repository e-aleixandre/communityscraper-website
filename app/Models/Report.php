<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Report
 *
 * @property int $id
 * @property string|null $filename
 * @property string $min_date
 * @property string $max_date
 * @property float $progress
 * @property int $completed
 * @property int $errored
 * @property string|null $token
 * @property int $pid
 * @property int $notify
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereErrored($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereMaxDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereMinDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereNotify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUserId($value)
 * @mixin \Eloquent
 */
class Report extends Model
{
    use HasFactory;

    protected $fillable = ["min_date", "max_date", "token", "notify"];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setToken(): Report
    {
        $this->token = Str::random(32);

        $this->save();

        return $this;
    }
}
