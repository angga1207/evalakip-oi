<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use App\Models\References\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{
    public function render()
    {
        $datas = User::where('id', '!=', 1)
            ->latest()
            ->get();
        $roles = Role::get();

        return view('livewire.user.index', [
            'datas' => $datas,
            'roles' => $roles,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Daftar Pengguna',
                'breadcrumbs' => [
                    ['name' => 'Manajemen Pengguna', 'url' => '#'],
                    ['name' => 'Pengguna', 'url' => route('users.index')],
                ],
                'addButton' => [
                    'name' => 'Tambah Pengguna',
                    'url' => route('users.create'),
                    'icon' => 'add',
                ],
            ]);
    }

    function confirmDelete($id)
    {
        LivewireAlert::title('Hapus Pengguna')
            ->text('Apakah Anda yakin ingin menghapus pengguna ini?')
            ->warning()
            ->allowOutsideClick(false)
            ->allowEscapeKey(false)
            ->confirmButtonText('Hapus')
            ->cancelButtonText('Batal')
            ->cancelButtonColor('#d33')
            ->confirmButtonColor('#3085d6')
            ->denyButtonText('Tidak')
            ->denyButtonColor('#d33')
            ->asConfirm()
            ->onConfirm('deleteData', ['id' => $id])
            ->show();
    }

    public function deleteData($data)
    {
        $itemId = $data['id'];
        DB::beginTransaction();
        try {
            $user = User::findOrFail($itemId);
            $user->delete();
            DB::commit();

            LivewireAlert::title('Data Pengguna berhasil dihapus.')
                ->text('Pengguna telah dihapus.')
                ->success()
                ->position('center')
                ->show();

            return redirect()->route('users.index');
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal menghapus data pengguna.')
                ->text('Terjadi kesalahan saat menghapus data pengguna.')
                ->position('center')
                ->error()
                ->show();
        }
    }

    function impersonate($id)
    {
        if (auth()->user()->id === 1) {
            $user = User::findOrFail($id);
            redirect()->route('impersonate', $user->id);
        } else {
            LivewireAlert::title('Akses Ditolak')
                ->text('Mau Ngapain Loe?')
                ->error()
                ->position('center')
                ->show();
            return;
        }
    }
}
