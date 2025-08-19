<?php

namespace App\Livewire\Grade;

use Livewire\Component;
use App\Models\Data\Grade;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{
    public function render()
    {
        $datas = Grade::orderBy('nilai', 'desc')->get();

        return view('livewire.grade.index', [
            'datas' => $datas,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Daftar Grade',
                'breadcrumbs' => [
                    ['name' => 'Referensi', 'url' => '#'],
                    ['name' => 'Grade', 'url' => route('grades.index')],
                ],
                'addButton' => [
                    'name' => 'Tambah Grade',
                    'url' => route('grades.create'),
                    'icon' => 'add',
                ],
            ]);
    }

    function confirmDelete($id)
    {
        LivewireAlert::title('Hapus Grade')
            ->text('Apakah Anda yakin ingin menghapus grade ini?')
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
            $grade = Grade::findOrFail($itemId);
            $grade->delete();
            DB::commit();

            LivewireAlert::title('Data Grade berhasil dihapus.')
                ->text('Grade telah dihapus.')
                ->success()
                ->position('center')
                ->show();

            return redirect()->route('grades.index');
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal menghapus data grade.')
                ->text('Terjadi kesalahan saat menghapus data grade.')
                ->position('center')
                ->error()
                ->show();
        }
    }
}
