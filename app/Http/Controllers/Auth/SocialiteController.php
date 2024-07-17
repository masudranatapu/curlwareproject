<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;

class SocialiteController extends Controller
{
    //
    public function googleRedirect()
    {
        return Socialite::driver('google')
            ->setHttpClient(new Client(['verify' => false]))
            ->redirect();
    }

    public function googleCallback()
    {
        $user = Socialite::driver('google')
            ->setHttpClient(new Client(['verify' => false]))
            ->user();

        $findUser = User::where('email', $user->email)->first();

        if ($findUser) {
            Auth::login($findUser);
            return redirect()->route('dashboard');
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
            ]);

            Auth::login($newUser);

            return redirect()->route('dashboard');
        }
    }
}
