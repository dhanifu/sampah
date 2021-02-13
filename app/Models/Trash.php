<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class Trash extends Model
{
    use SoftDeletes;
    use Uuid;

    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
