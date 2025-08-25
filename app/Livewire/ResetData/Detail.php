<?php

namespace App\Livewire\ResetData;

use Livewire\Component;
use App\Models\Data\Jawaban;
use Livewire\Attributes\Url;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use App\Models\References\Instance;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Detail extends Component
{
    #[Url(null, true)]
    public $type = null;
    public $search = '';
    public $periode = null;

    function mount()
    {
        $this->periode = Periode::where('is_active', true)->first();
        if (!$this->periode) {
            abort(404, 'Periode aktif tidak ditemukan. Silakan buat periode terlebih dahulu.');
        }

        if (!$this->type || !in_array($this->type, ['penilaian', 'evaluasi'])) {
            abort(404, 'Tipe reset data tidak valid. Pilih antara "penilaian" atau "evaluasi".');
        }
    }

    public function render()
    {
        $datas = Instance::search($this->search)
            ->orderBy('unit_id', 'asc')
            ->get();

        return view('livewire.reset-data.detail', [
            'datas' => $datas,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Reset Data ' . ucfirst($this->type),
                'breadcrumbs' => [
                    ['name' => 'Reset Data', 'url' => '#'],
                    ['name' => ucfirst($this->type), 'url' => route('reset-data', ['type' => $this->type])],
                ],
                // 'addButton' => [
                //     'name' => 'Tambah Kriteria',
                //     'url' => route('criterias.create'),
                //     'icon' => 'add',
                // ],
            ]);
    }


    function confirmReset($id)
    {
        LivewireAlert::title('Reset Data')
            ->text('Apakah Anda yakin ingin mereset data ini?')
            ->warning()
            ->allowOutsideClick(false)
            ->allowEscapeKey(false)
            ->confirmButtonText('Reset')
            ->cancelButtonText('Batal')
            ->cancelButtonColor('#d33')
            ->confirmButtonColor('#3085d6')
            ->denyButtonText('Tidak')
            ->denyButtonColor('#d33')
            ->asConfirm()
            ->onConfirm('resetData', ['id' => $id])
            ->show();
    }

    public function resetData($data)
    {
        $instanceId = $data['id'];
        DB::beginTransaction();
        try {
            if ($this->type == 'penilaian') {
                Jawaban::where('instance_id', $instanceId)
                    ->where('ref_periode_id', $this->periode->id)
                    ->update([
                        'is_submitted' => false,
                        'is_verified' => false,
                    ]);
            }

            if ($this->type == 'evaluasi') {
                Jawaban::where('instance_id', $instanceId)
                    ->where('ref_periode_id', $this->periode->id)
                    ->update([
                        'is_verified' => false,
                    ]);
            }
            DB::commit();

            LivewireAlert::title('Berhasil.')
                ->text('Data berhasil direset.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();

            return redirect()->route('reset-data', ['type' => $this->type]);
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal mereset data.')
                ->text('Terjadi kesalahan saat mereset data.')
                ->position('top-end')
                ->error()
                ->show();
        }
    }
}
