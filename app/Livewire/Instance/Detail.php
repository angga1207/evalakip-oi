<?php

namespace App\Livewire\Instance;

use App\Models\User;
use GuzzleHttp\Client;
use Livewire\Component;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use App\Models\References\Instance;
use App\Models\References\Unit;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Detail extends Component
{
    public $id, $instance, $isCreate = true;
    public $rawSemestaUsers = [], $semestaUsers = [], $searchSemestaUser = '';

    function mount($id = null)
    {
        if ($id) {
            $this->isCreate = false;
            $this->instance = Instance::findOrFail($id);
            $this->instance = $this->instance->toArray();
            $this->id = $id;
        } else {
            $this->instance = new Instance();
        }
        // dd($this->instance);
    }

    public function render()
    {
        $users = [];
        $users = User::whereIn('role_id', [2, 4])
            ->where('instance_id', $this->instance['id'])
            ->get();
        $units = Unit::orderBy('name')
            ->get();

        return view('livewire.instance.detail', [
            'users' => $users,
            'units' => $units,
        ])
            ->layout('components.layouts.app', [
                'title' => $this->isCreate ? 'Tambah Instansi' : 'Edit Instansi',
                'breadcrumbs' => [
                    ['name' => 'Instansi', 'url' => route('instansi.index')],
                    ['name' => $this->isCreate ? 'Tambah Instansi' : 'Edit Instansi', 'url' => '#'],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('instansi.index'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }

    function updated($field)
    {
        if ($field == 'searchSemestaUser') {
            $this->semestaUsers = collect($this->rawSemestaUsers)
                ->filter(function ($user) {
                    return str_contains(strtolower($user['nama_lengkap']), strtolower($this->searchSemestaUser)) ||
                        str_contains(strtolower($user['nip']), strtolower($this->searchSemestaUser));
                })->values()->all();
            // dd($this->semestaUsers);
        }

        if ($field == 'instance.unit_id') {
            // update the instance's unit_id
            $data = Instance::findOrFail($this->instance['id']);
            $data->unit_id = $this->instance['unit_id'];
            $data->save();
            LivewireAlert::title('Sukses!')
                ->text('Unit instansi berhasil diperbarui.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    function _FetchSemestaUsers()
    {
        $this->semestaUsers = [];
        $this->rawSemestaUsers = [];
        if (!$this->instance['id_eoffice']) {
            LivewireAlert::title('Peringatan!')
                ->text('Instansi belum disimpan, silakan simpan instansi terlebih dahulu.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $uri = 'https://semesta.oganilirkab.go.id/api/daftar-pegawai';
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'PostmanRuntime/7.44.1',
                'x-api-key' => '!@#Op3nAp1K3584n9p0l',
            ])->post($uri, [
                'id_skpd' => $this->instance['id_eoffice'],
            ]);
            // dd($response->status(), $response->body());
            if ($response->status() == 200) {
                $this->semestaUsers = $response->json()['data'];
                $this->rawSemestaUsers = $this->semestaUsers;
            } else {
                LivewireAlert::title('Peringatan!')
                    ->text('Gagal mengambil data pengguna semesta.')
                    ->warning()
                    ->toast()
                    ->position('top-end')
                    ->show();
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            dd($e->response->status(), $e->response->body());
        }
    }

    function linkUser($id)
    {
        $userSemesta = collect($this->semestaUsers)->firstWhere('id', $id);
        if (!$userSemesta) {
            LivewireAlert::title('Peringatan!')
                ->text('Pengguna tidak ditemukan.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }
        // dd($userSemesta);

        DB::beginTransaction();
        try {
            $user = User::where('username', $userSemesta['nip'])->first();
            if (!$user) {
                $user = new User();
                // $user->role_id = 2; // Assuming 2 is the role ID for 'Admin'
                $user->role_id = $userSemesta['kepala_skpd'] == 'Y' ? 4 : 2; // 4 for Kepala SKPD, 2 for Admin
                $user->username = $userSemesta['nip'];
            }
            $user->name = $userSemesta['nama_lengkap'];
            $user->email = $userSemesta['email'] ?? $userSemesta['nip'] . '@oganilirkab.go.id';
            $user->jabatan = $userSemesta['jabatan'];
            $user->image = $userSemesta['foto_pegawai'] ?? 'assets/images/avatars/07.png';
            $user->instance_id = $this->instance['id'];
            $user->no_hp = $userSemesta['no_hp'] ?? null;
            $user->password = bcrypt(rand(10000000, 99999999)); // Set a default password or handle it as needed
            $user->save();

            DB::commit();
            LivewireAlert::title('Sukses!')
                ->text('Pengguna berhasil ditambahkan ke instansi.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            LivewireAlert::title('Error!')
                ->text('Terjadi kesalahan saat menambahkan pengguna: ' . $e->getMessage())
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }
    }

    function unlinkUser($userId)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($userId);
            $user->delete();

            DB::commit();
            LivewireAlert::title('Sukses!')
                ->text('Pengguna berhasil dihapus dari instansi.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error!')
                ->text('Terjadi kesalahan saat menghapus pengguna: ' . $e->getMessage())
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }
}
