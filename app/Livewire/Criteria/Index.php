<?php

namespace App\Livewire\Criteria;

use App\Models\Components\Component as ComponentsComponent;
use App\Models\Data\Kriteria;
use Livewire\Component;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{
    public $periode = null;
    public $search = '', $filterSubComponent = null;

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
        $subComponents = collect();
        if ($this->periode) {
            $datas = Kriteria::search($this->search)
                ->with(['Periode', 'Component', 'Jawaban'])
                ->where('ref_periode_id', $this->periode->id)
                ->oldest()
                ->get();

            $subComponents = ComponentsComponent::whereNotNull('parent_id')
                ->where('ref_periode_id', $this->periode->id)
                ->get();
        }
        // dd($datas);

        return view('livewire.criteria.index', [
            'datas' => $datas,
            'subComponents' => $subComponents,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Daftar Kriteria',
                'breadcrumbs' => [
                    ['name' => 'Kriteria', 'url' => '#'],
                    ['name' => 'Kriteria', 'url' => route('criterias.index')],
                ],
                'addButton' => [
                    'name' => 'Tambah Kriteria',
                    'url' => route('criterias.create'),
                    'icon' => 'add',
                ],
            ]);
    }

    function confirmDelete($id)
    {
        LivewireAlert::title('Hapus Kriteria')
            ->text('Apakah Anda yakin ingin menghapus kriteria ini?')
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
            $data = Kriteria::findOrFail($itemId);
            $data->delete();
            DB::commit();

            LivewireAlert::title('Data Kriteria berhasil dihapus.')
                ->text('Kriteria telah dihapus.')
                ->success()
                ->position('center')
                ->show();

            return redirect()->route('criterias.index');
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal menghapus data kriteria.')
                ->text('Terjadi kesalahan saat menghapus data kriteria.')
                ->position('center')
                ->error()
                ->show();
        }
    }
}
