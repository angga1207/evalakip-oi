<?php

namespace App\Livewire\Component;

use Livewire\Component;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Components\Component as ModelComponent;

class Index extends Component
{
    public $periode = null;
    public $search = '';

    function mount()
    {
        $this->periode = Periode::where('is_active', true)->first();
        if (!$this->periode) {
            LivewireAlert::title('Periode Aktif Tidak Ditemukan')
                ->text('Silakan buat periode terlebih dahulu.')
                ->warning()
                ->withConfirmButton('Buat Periode', 'redirectToCreatePeriode')
                ->confirmButtonColor('#3085d6')
                ->onConfirm('redirectToCreatePeriode')
                ->allowOutsideClick(false)
                ->allowEscapeKey(false)
                ->timer(0)
                ->show();
        }
    }

    function redirectToCreatePeriode()
    {
        return redirect()->route('periode.index');
    }

    public function render()
    {
        $datas = collect();
        if ($this->periode) {
            $datas = ModelComponent::search($this->search)
                ->with('Children')
                ->with('RefPeriode', 'Parent')
                ->where('ref_periode_id', $this->periode->id)
                ->where('parent_id', null)
                ->oldest()
                ->get();
        }

        return view('livewire.component.index', [
            'datas' => $datas,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Daftar Komponen',
                'breadcrumbs' => [
                    ['name' => 'Komponen', 'url' => '#'],
                    ['name' => 'Komponen', 'url' => route('components.index')],
                ],
                'addButton' => [
                    'name' => 'Tambah Komponen',
                    'url' => route('components.create'),
                    'icon' => 'add',
                ],
            ]);
    }

    function confirmDelete($id)
    {
        LivewireAlert::title('Hapus Komponen')
            ->text('Apakah Anda yakin ingin menghapus komponen ini?')
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
            $component = ModelComponent::findOrFail($itemId);
            $component->delete();
            DB::commit();

            LivewireAlert::title('Data Komponen berhasil dihapus.')
                ->text('Komponen telah dihapus.')
                ->success()
                ->position('center')
                ->show();

            return redirect()->route('components.index');
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal menghapus data komponen.')
                ->text('Terjadi kesalahan saat menghapus data komponen.')
                ->position('center')
                ->error()
                ->show();
        }
    }
}
