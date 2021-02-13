<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Member;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::with('village:id,name as desa')->select(['id','name','village_id'])->where('name', 'like', "%{$request->name}%")
                    ->orderBy('name', 'ASC')->get()->take(5);
        $result = [];

        foreach ($members as $member) {
            $result[] = [
                "id" => $member->id,
                "text" => $member->name . " (" . $member->village->desa . ")",
                "name" => $member->name,
            ];
        }

        return $result;
    }
}
