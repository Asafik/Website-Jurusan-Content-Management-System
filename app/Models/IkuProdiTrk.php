<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class IkuProdiTrk extends Model
{
    use HasFactory;
    use HasHashid;
    use HashidRouting;

    protected $appends = ['hashid'];
    protected $guarded = [];

    public function ikuProdiTrk()
    {
        return $this->hasMany(IkuProdiTrk::class);
    }
}
