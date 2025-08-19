<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Me extends Component
{
    public $user;

    function mount()
    {
        $this->user = auth()->user()->toArray();
        $this->user['password'] = ''; // Clear password for security
        $this->user['password_confirmation'] = ''; // Clear password for security
    }

    public function render()
    {
        return view('livewire.user.me')
            ->layout('components.layouts.app', [
                'title' => 'Profile Saya',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'url' => route('dashboard')],
                    ['name' => 'Profile Saya', 'url' => '#'],
                ],
            ]);
    }

    function save()
    {
        $this->validate([
            'user.name' => 'required|string|max:255',
            'user.username' => 'required|string|max:255|unique:users,username,' . $this->user['id'],
            'user.email' => 'required|email|unique:users,email,' . $this->user['id'],
            'user.password' => 'nullable|string|min:8|confirmed',
        ], [], [
            'user.name' => 'Nama Lengkap',
            'user.username' => 'Nama Pengguna',
            'user.email' => 'Email',
            'user.password' => 'Kata Sandi',
        ]);

        $user = User::findOrFail($this->user['id']);
        $user->name = $this->user['name'];
        $user->username = $this->user['username'];
        $user->email = $this->user['email'];
        if ($this->user['password']) {
            $user->password = bcrypt($this->user['password']);
        }
        $user->save();

        LivewireAlert::title('Berhasil!')
            ->text('Profil Anda telah berhasil diperbarui.')
            ->success()
            ->show();

        $this->user['password'] = '';
        $this->user['password_confirmation'] = '';
    }
}
