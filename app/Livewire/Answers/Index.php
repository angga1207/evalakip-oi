<?php

namespace App\Livewire\Answers;

use Livewire\Component;
use App\Models\References\Answer;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{
    public function render()
    {
        $datas = Answer::latest()->get();

        return view('livewire.answers.index', [
            'datas' => $datas,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Daftar Jawaban',
                'breadcrumbs' => [
                    ['name' => 'Referensi', 'url' => '#'],
                    ['name' => 'Jawaban', 'url' => route('answers.index')],
                ],
                'addButton' => [
                    'name' => 'Tambah Jawaban',
                    'url' => route('answers.create'),
                    'icon' => 'add',
                ],
            ]);
    }

    function confirmDelete($id)
    {
        LivewireAlert::title('Hapus Jawaban')
            ->text('Apakah Anda yakin ingin menghapus jawaban ini?')
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
            $answer = Answer::findOrFail($itemId);
            $answer->delete();
            DB::commit();

            LivewireAlert::title('Data Jawaban berhasil dihapus.')
                ->text('Jawaban telah dihapus.')
                ->success()
                ->position('center')
                ->show();

            return redirect()->route('answers.index');
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal menghapus data jawaban.')
                ->text('Terjadi kesalahan saat menghapus data jawaban.')
                ->position('center')
                ->error()
                ->show();
        }
    }
}
