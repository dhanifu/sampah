<?php

use App\Models\Type;
use App\Models\Village;
use App\Models\Member;

function typeTrashCount(){
    $data = Type::onlyTrashed()->count();
    return $data;
}

function villageTrashCount(){
    $data = Village::onlyTrashed()->count();
    return $data;
}

function memberTrashCount(){
    $data = Member::onlyTrashed()->count();
    return $data;
}

function get_village_name($village_id){
    $data = Village::find($village_id);
    return $data->name;
}

function localDate(string $date): String
{
	return date('d M Y', strtotime($date));
}

function localDateTime(string $date): String
{
    return date('d M Y | H:i', strtotime($date));
}