<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class Village extends Model
{
    use SoftDeletes;
    use Uuid;

    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
}
