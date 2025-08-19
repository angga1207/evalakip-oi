<?php

namespace App\Livewire\User;

use App\Models\References\Instance;
use App\Models\References\Role;
use App\Models\User;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class Detail extends Component
{
    public $id, $user, $isCreate = true;
    public $selectedInstances = [];

    function mount($id = null)
    {
        if ($id) {
            $this->isCreate = false;
            $this->user = User::findOrFail($id);

            if ($this->user['role_id'] == 3) {
                $this->selectedInstances = $this->user['Instances']->pluck('id')->toArray();
            } else {
                $this->selectedInstances = [];
            }

            $this->user = $this->user->toArray();
            $this->user['password'] = ''; // Clear password for security
            $this->user['password_confirmation'] = ''; // Clear password for security
            $this->id = $id;
        } else {
            $this->user = new User();
            $this->user = $this->user->toArray();
            $this->user['role_id'] = '';
            $this->user['instance_id'] = '';
            $this->user['password'] = '';
            $this->user['password_confirmation'] = '';
        }
        // dd($this->instance);
    }

    public function render()
    {
        $roles = Role::get();
        $instances = Instance::all();

        return view('livewire.user.detail', [
            'roles' => $roles,
            'instances' => $instances,
        ])
            ->layout('components.layouts.app', [
                'title' => $this->isCreate ? 'Tambah Pengguna' : 'Edit Pengguna',
                'breadcrumbs' => [
                    ['name' => 'Pengguna', 'url' => route('users.index')],
                    ['name' => $this->isCreate ? 'Tambah Pengguna' : 'Edit Pengguna', 'url' => '#'],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('users.index'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }

    function save()
    {
        // dd($this->selectedInstances);
        // dd($this->user);
        if ($this->isCreate) {
            $this->validate([
                'user.name' => 'required|string|max:255',
                'user.username' => 'required|string|max:255|unique:users,username',
                'user.email' => 'required|email|unique:users,email',
                'user.role_id' => 'required|exists:roles,id',
                'user.instance_id' => 'required|exists:instances,id',
                'user.password' => 'required|string|min:8|confirmed',
            ], [], [
                'user.name' => 'Nama Lengkap',
                'user.username' => 'Nama Pengguna',
                'user.email' => 'Email',
                'user.role_id' => 'Jenis Pengguna',
                'user.instance_id' => 'Instansi',
                'user.password' => 'Kata Sandi',
            ]);

            $user = new User();
            $user->name = $this->user['name'];
            $user->username = $this->user['username'];
            $user->email = $this->user['email'];
            $user->role_id = $this->user['role_id'];
            $user->instance_id = $this->user['instance_id'];
            $user->password = bcrypt($this->user['password']);
            $user->image = 'https://ui-avatars.com/api/?name=' . urlencode($this->user['name']) . '&background=random';
            $user->save();

            LivewireAlert::title('Berhasil!')
                ->text('Pengguna telah berhasil ditambahkan.')
                ->success()
                ->show();

            return redirect()->route('users.index');
        } else {
            $this->validate([
                'user.name' => 'required|string|max:255',
                'user.username' => 'required|string|max:255|unique:users,username,' . $this->id,
                'user.email' => 'required|email|unique:users,email,' . $this->id,
                'user.role_id' => 'required|exists:roles,id',
                'user.instance_id' => 'required|exists:instances,id',
                'user.password' => 'nullable|string|min:8|confirmed',
            ], [], [
                'user.name' => 'Nama Lengkap',
                'user.username' => 'Nama Pengguna',
                'user.email' => 'Email',
                'user.role_id' => 'Jenis Pengguna',
                'user.instance_id' => 'Instansi',
                'user.password' => 'Kata Sandi',
            ]);

            $user = User::findOrFail($this->id);
            $user->name = $this->user['name'];
            $user->username = $this->user['username'];
            $user->email = $this->user['email'];
            $user->role_id = $this->user['role_id'];
            $user->instance_id = $this->user['instance_id'];
            if ($this->user['password']) {
                $user->password = bcrypt($this->user['password']);
            }
            $user->save();

            if ($user->role_id == 3) {
                $user->Instances()->sync($this->selectedInstances);
            } else {
                $user->Instances()->detach();
            }

            LivewireAlert::title('Berhasil!')
                ->text('Pengguna telah berhasil diperbarui.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }
}
