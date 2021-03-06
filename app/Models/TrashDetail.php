<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class TrashDetail extends Model
{
    use SoftDeletes;
    use Uuid;

    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function trash()
    {
        return $this->belongsTo(Trash::class, 'trash_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
