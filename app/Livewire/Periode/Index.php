<?php

namespace App\Livewire\Periode;

use Livewire\Component;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{
    public function render()
    {
        $datas = Periode::latest()->get();

        return view('livewire.periode.index', [
            'datas' => $datas,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Daftar Periode',
                'breadcrumbs' => [
                    ['name' => 'Referensi', 'url' => '#'],
                    ['name' => 'Periode', 'url' => route('periode.index')],
                ],
                'addButton' => [
                    'name' => 'Tambah Periode',
                    'url' => route('periode.create'),
                    'icon' => 'add',
                ],
            ]);
    }

    function confirmDelete($id)
    {
        LivewireAlert::title('Hapus Periode')
            ->text('Apakah Anda yakin ingin menghapus periode ini?')
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
            $periode = Periode::findOrFail($itemId);
            $periode->delete();
            DB::commit();

            LivewireAlert::title('Data Periode berhasil dihapus.')
                ->text('Periode telah dihapus.')
                ->success()
                ->position('center')
                ->show();

            return redirect()->route('periode.index');
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal menghapus data periode.')
                ->text('Terjadi kesalahan saat menghapus data periode.')
                ->position('center')
                ->error()
                ->show();
        }
    }
}
