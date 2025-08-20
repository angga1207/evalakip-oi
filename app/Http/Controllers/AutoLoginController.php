<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AutoLoginController extends Controller
{
    function autoLogin(Request $request, $id)
    {
        $nip = $id;
        // header request api-key
        $apiKey = $request->header('api-key');
        if ($apiKey !== '4uT0_l0g!Nz-_3v@l4k1P') {
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {
            $user = User::where('username', $nip)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            auth()->login($user);
            return redirect()->route('dashboard');
        }
    }
}
