<?php

use App\Models\Type;
use App\Models\Village;
use App\Models\Member;
use App\Models\Trash;

function typeTrashCount()
{
    $data = Type::onlyTrashed()->count();
    return $data;
}

function villageTrashCount()
{
    $data = Village::onlyTrashed()->count();
    return $data;
}

function memberTrashCount()
{
    $data = Member::onlyTrashed()->count();
    return $data;
}

function historyTrashCount()
{
    $data = Trash::onlyTrashed()->count();
    return $data;
}

function get_village_name($village_id)
{
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

function getTime(string $time): String
{
    return date('H:i', strtotime($time));
}

function greeting(string $name): String
{
    date_default_timezone_set('Asia/Jakarta');

    $jam = date('H');

    if ($jam >= 18) {
        $greeting = "Selamat Malam " . $name;
    } elseif ($jam >= 12) {
        $greeting = "Selamat Siang " . $name;
    } elseif ($jam < 12) {
        $greeting = "Selamat Pagi " . $name;
    }
    return $greeting;
}
