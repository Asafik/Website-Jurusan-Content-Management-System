<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Achievement extends Model
{
    use HasFactory;
    use HasHashid;
    use HashidRouting;

    protected $appends = ['hashid'];
    protected $guarded = [];

    public function achievementType()
    {
        return $this->belongsTo(AchievementType::class);
    }

    public function achievementLevel()
    {
        return $this->belongsTo(AchievementLevel::class);
    }

    public function achievementProgramStudi()
    {
        return $this->belongsTo(AchievementProgramStudi::class);
    }
}
