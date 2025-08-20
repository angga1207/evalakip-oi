<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AutoLoginController extends Controller
{
    function autoLogin(Request $request, $id)
    {
        $nip = $id;
        $user = User::where('username', $nip)->first();
        if (!$user) {
            abort(404, 'User not found');
            return;
        }
        auth()->login($user);
        return redirect()->route('dashboard');

        // $apiKey = $request->header('api-key');
        // if ($apiKey !== '4uT0_l0g!Nz-_3v@l4k1P') {
        //     abort(401, 'User not found');
        //     return;
        // } else {
        //     $user = User::where('username', $nip)->first();
        //     if (!$user) {
        //         abort(404, 'User not found');
        //         return;
        //     }
        //     auth()->login($user);
        //     return redirect()->route('dashboard');
        // }
    }
}
