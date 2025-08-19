<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Login extends Component
{
    public $username, $password, $remember = true;
    // 198602022015032002

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth', [
                'title' => 'Login',
                'description' => 'Please enter your credentials to login.',
            ]);
    }

    function login()
    {
        $this->validate([
            'username' => 'required|alpha_num',
            'password' => 'required|string',
        ], [], [
            'username' => 'NIP',
            'password' => 'Kata Sandi',
        ]);

        // Perform login action
        if ($this->username == 'developer') {
            Auth::attempt([
                'username' => $this->username,
                'password' => $this->password,
            ], $this->remember);

            if (Auth::check()) {
                return redirect()->route('dashboard');
            } else {
                LivewireAlert::title('Login Gagal')
                    ->text('NIP atau Kata Sandi yang Anda masukkan salah.')
                    ->error()
                    ->position('center')
                    ->show();

                $this->reset(['password']);
            }
        } else {
            $uri = 'https://semesta.oganilirkab.go.id/api/auth-user-evalakip';
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'PostmanRuntime/7.44.1',
            ])->post($uri, [
                'username' => $this->username,
                'password' => $this->password,
            ]);

            if ($response->status() == 200) {
                $data = $response->json();
                $user = User::where('username', $data['atribut_user']['username'])->first();
                if (!$user) {
                    LivewireAlert::title('Peringatan!')
                        ->text('Pengguna tidak ditemukan. Silakan hubungi administrator.')
                        ->warning()
                        ->toast()
                        ->position('top-end')
                        ->show();
                    return;
                }
                // Login the user
                Auth::loginUsingId($user->id, $this->remember);

                if (Auth::check()) {
                    return redirect()->route('dashboard');
                } else {
                    LivewireAlert::title('Login Gagal')
                        ->text('NIP atau Kata Sandi yang Anda masukkan salah.')
                        ->error()
                        ->position('center')
                        ->show();

                    $this->reset(['password']);
                }
            } else {
                LivewireAlert::title('Login Gagal')
                    ->text('NIP atau Kata Sandi yang Anda masukkan salah.')
                    ->error()
                    ->position('center')
                    ->show();

                $this->reset(['password']);
            }
        }
    }
}
