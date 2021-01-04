<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class Member extends Model
{
    use SoftDeletes;
    use Uuid;

    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function village()
    {
        return $this->belongsTo('App\Models\Village', 'village_id');
    }
}
