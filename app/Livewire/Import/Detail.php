<?php

namespace App\Livewire\Import;

use Livewire\Component;
use App\Models\Data\Kriteria;
use Livewire\WithFileUploads;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Components\Component as ModelComponent;
use App\Models\References\Answer;

class Detail extends Component
{
    use WithFileUploads;
    public $periode = null;
    public $dataKomponen = [];
    public $dataKriteria = [];

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
        return view('livewire.import.detail')
            ->layout('components.layouts.app', [
                'title' => 'Import Data',
                'breadcrumbs' => [
                    ['name' => 'Referensi', 'url' => '#'],
                    ['name' => 'Import', 'url' => route('import')],
                ],
                // 'addButton' => [
                //     'name' => 'Tambah Kriteria',
                //     'url' => route('criterias.create'),
                //     'icon' => 'add',
                // ],
            ]);
    }

    function importKomponen()
    {
        $this->validate([
            'dataKomponen.fileExcel' => 'required|file|mimes:xlsx,xls,csv',
        ], [], [
            'dataKomponen.fileExcel' => 'File Excel',
        ]);

        DB::beginTransaction();
        try {
            $dataExcel = [];
            $file = $this->dataKomponen['fileExcel'];
            $filePath = $file->store('temp', 'public');
            $fileFullPath = storage_path('app/public/' . $filePath);
            $dataExcel = \Maatwebsite\Excel\Facades\Excel::toArray([], $fileFullPath)[0];
            $dataExcel = collect($dataExcel)->skip(1)->values();
            $isMain = true;
            if ($dataExcel->first()[2] == 'Nama Sub Komponen') {
                $isMain = false;
            }
            $dataExcel = collect($dataExcel)->skip(1)->values()->toArray();

            foreach ($dataExcel as $row) {
                if (empty($row[0])) continue; // Skip empty rows

                if ($isMain) {
                    ModelComponent::updateOrCreate(
                        [
                            'nama' => $row[1],
                            'ref_periode_id' => $this->periode->id
                        ],
                        [
                            'parent_id' => null,
                            'bobot' => $row[2],
                        ]
                    );
                }

                if ($isMain == false) {
                    $parent = ModelComponent::where('nama', $row[1])
                        ->where('ref_periode_id', $this->periode->id)
                        ->first();
                    ModelComponent::updateOrCreate(
                        [
                            'nama' => $row[2],
                            'ref_periode_id' => $this->periode->id
                        ],
                        [
                            'parent_id' => $parent ? $parent->id : null,
                            'bobot' => $row[3],
                        ]
                    );
                }
            }

            DB::commit();
            LivewireAlert::title('Data Berhasil Diimpor')
                ->text('Data komponen telah berhasil diimpor.')
                ->success()
                ->position('top-end')
                ->toast()
                ->show();
            $this->dataKomponen = [];
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            LivewireAlert::title('Gagal Mengimpor Data')
                ->text($e->getMessage())
                ->show();
            return;
        }
    }

    function importKriteria()
    {
        $this->validate([
            'dataKriteria.fileExcel' => 'required|file|mimes:xlsx,xls,csv',
        ], [], [
            'dataKriteria.fileExcel' => 'File Excel',
        ]);

        DB::beginTransaction();
        try {
            $dataExcel = [];
            $file = $this->dataKriteria['fileExcel'];
            $filePath = $file->store('temp', 'public');
            $fileFullPath = storage_path('app/public/' . $filePath);
            $dataExcel = \Maatwebsite\Excel\Facades\Excel::toArray([], $fileFullPath)[0];
            $dataExcel = collect($dataExcel)->skip(1)->values();

            if (($dataExcel->first()[1] != 'Nama Sub Komponen') && ($dataExcel->first()[2] != 'Nama Kriteria') && ($dataExcel->first()[3] != 'Status Nilai') && ($dataExcel->first()[4] != 'Pilihan Jawaban')) {
                LivewireAlert::title('Format File Tidak Sesuai')
                    ->text('Pastikan file Excel yang diunggah sesuai dengan format yang telah ditentukan.')
                    ->warning()
                    ->show();
                return;
            }

            $dataExcel = collect($dataExcel)->skip(1)->values()->toArray();
            foreach ($dataExcel as $row) {
                if (empty($row[0])) continue; // Skip empty rows

                $subComponent = ModelComponent::where('nama', $row[1])
                    ->where('ref_periode_id', $this->periode->id)
                    ->first();

                if (!$subComponent) {
                    LivewireAlert::title('Sub Komponen Tidak Ditemukan')
                        ->text('Pastikan sub komponen "' . $row[1] . '" sudah ada.')
                        ->warning()
                        ->show();
                    return;
                }

                // $refAnswer where label is case-insensitive
                $refAnswer = Answer::where('label', 'ILIKE', $row[4])->first();

                Kriteria::updateOrCreate(
                    [
                        'nama' => $row[2],
                        'ref_periode_id' => $this->periode->id,
                        'component_id' => $subComponent->id,
                    ],
                    [
                        'is_active' => $row[3] == 'Ya' ? true : false,
                        'ref_jawaban_id' => $refAnswer ? $refAnswer->id : null,
                        'bobot' => 0,
                    ]
                );
            }

            DB::commit();
            LivewireAlert::title('Data Berhasil Diimpor')
                ->text('Data kriteria telah berhasil diimpor.')
                ->success()
                ->position('top-end')
                ->toast()
                ->show();
            $this->dataKriteria = [];
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            LivewireAlert::title('Gagal Mengimpor Data')
                ->text($e->getMessage())
                ->show();
            return;
        }
    }
}
