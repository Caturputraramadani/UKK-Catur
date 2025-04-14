<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function search(Request $request)
    {
        $phone = $request->input('phone');
        $member = Member::where('no_telephone', $phone)->first();

        return response()->json($member);
    }
}
