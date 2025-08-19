<?php

namespace App\Livewire\Evaluasi;

use Livewire\Component;
use App\Models\Data\Jawaban;
use Livewire\Attributes\Url;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use App\Models\References\Instance;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Components\Component as ModelComponent;

class Detail extends Component
{
    #[Url(null, true)]
    public $instance;
    public $periode = null;
    public $dataQuestionaries = [];
    public $dataPenilaian = [];
    public $totalSkor = 0, $totalBobot = 0;
    public $isSubmitted = false;
    public $isVerified = false;

    function mount()
    {
        $this->periode = Periode::where('is_active', true)->first();
        if (!$this->periode) {
            LivewireAlert::title('Periode Aktif Tidak Ditemukan')
                ->text('Harap Hubungi Admin!')
                ->warning()
                ->confirmButtonColor('#3085d6')
                ->allowOutsideClick(false)
                ->allowEscapeKey(false)
                ->timer(0)
                ->show();
        }
    }

    function _getDataQuestionaries()
    {
        // sleep(1);
        $instance = Instance::find($this->instance);
        if ($instance->id == null) {
            LivewireAlert::title('Instance Tidak Ditemukan')
                ->text('Harap Hubungi Admin!')
                ->warning()
                ->confirmButtonColor('#3085d6')
                ->allowOutsideClick(false)
                ->allowEscapeKey(false)
                ->timer(0)
                ->show();
            return;
        }
        $this->dataQuestionaries = ModelComponent::where('ref_periode_id', $this->periode->id)
            ->with(['Children', 'Children.Criterias', 'Children.Criterias.Jawaban', 'Children.Criterias.Jawaban.Values'])
            ->where('parent_id', null)
            ->get()
            ->where('bobot', '!=', 100)
            ->toArray();
        $this->dataPenilaian = [];
        $totalBobot = 0;
        $totalSkor = 0.00;

        foreach ($this->dataQuestionaries as $key => $questionary) {
            // calculate score = rata-rata skor dari semua subkomponen dikali dengan bobot komponen
            $PluckCriteriasIds = collect($questionary['children'])->pluck('criterias')->collapse()->pluck('id')->toArray();
            $jawaban = Jawaban::where('ref_periode_id', $this->periode->id)
                ->whereIn('criteria_id', $PluckCriteriasIds)
                ->where('instance_id', $instance->id)
                ->get();
            $countActiveCriterias = collect($questionary['children'])->pluck('criterias')->collapse()->where('is_active', true)->count();
            $calculatedScore = 0.00;
            if ($jawaban->count() > 0) {
                $calculatedScore = $jawaban->sum('skor') * floatval($questionary['bobot']) / $countActiveCriterias;
            }
            $totalSkor += $calculatedScore;

            $this->dataPenilaian[$key] = [
                'nama' => $questionary['nama'],
                'children' => [],
                'skor' => $calculatedScore,
            ];
            foreach ($questionary['children'] as $keyChild => $child) {

                // calculated score = rata-rata skor dari semua kriteria di subkomponen dikali dengan bobot subkomponen
                $jawaban = Jawaban::where('ref_periode_id', $this->periode->id)
                    ->whereIn('criteria_id', collect($child['criterias'])->pluck('id')->toArray())
                    ->where('instance_id', $instance->id)
                    ->get();

                $calculatedScore = 0.00;
                if ($jawaban->count() > 0) {
                    $calculatedScore = $jawaban->sum('skor') * floatval($child['bobot']) / collect($child['criterias'])->where('is_active', true)->count();
                }

                $this->dataPenilaian[$key]['children'][$keyChild] = [
                    'nama' => $child['nama'],
                    'penjelasan' => $child['keterangan'],
                    'jawaban' => $child['criterias'][0]['Jawaban'] ?? null,
                    'skor' => $calculatedScore,
                ];
                $totalBobot += floatval($child['bobot']) ?? 0;

                foreach ($child['criterias'] as $keyCriteria => $criteria) {
                    $jawaban = Jawaban::where('ref_periode_id', $this->periode->id)
                        ->where('criteria_id', $criteria['id'])
                        ->where('instance_id', $instance->id)
                        ->first();
                    if ($jawaban && $jawaban->is_submitted) {
                        $this->isSubmitted = true;
                    }

                    // total skor = rata-rata skor dari semua kriteria di subkomponen dikali dengan bobot subkomponen
                    $calculatedScore = floatval($jawaban->skor ?? 0) * floatval($child['bobot']) / collect($child['criterias'])->where('is_active', true)->count();
                    // $totalSkor += $calculatedScore;


                    $this->dataPenilaian[$key]['children'][$keyChild]['criterias'][$keyCriteria] = [
                        'criteria_id' => $criteria['id'],
                        'ref_jawaban_id' => $criteria['ref_jawaban_id'],
                        'nama' => $criteria['nama'],
                        'penjelasan' => $criteria['penjelasan'],
                        'jawaban' => $jawaban ? $jawaban->skor : null,
                        'skor' => $jawaban ? $jawaban->skor : null,
                        'calculated_score' => $calculatedScore,
                        'catatan' => $jawaban ? $jawaban->catatan : null,
                        'new_evidence' => null,
                        'evidence' => $jawaban ? $jawaban->evidence : null,
                    ];
                }
            }
        }

        $this->totalSkor = $totalSkor;
        $this->totalBobot = $totalBobot;
        // dd($this->isSubmitted ? 'Data sudah disubmit' : 'Data belum disubmit');
        // dd($this->dataQuestionaries, $this->dataPenilaian);
        // dd($this->dataPenilaian);
    }

    public function render()
    {
        $instance = Instance::find($this->instance);
        return view('livewire.evaluasi.detail')
            ->layout('components.layouts.app', [
                'title' => 'Evaluasi ' . ($instance ? $instance->name : ''),
                'breadcrumbs' => [
                    ['name' => 'Input', 'url' => '#'],
                    ['name' => 'EVALUASI ' . ($instance ? $instance->name : ''), 'url' => route('evaluasi')],
                ],
                // 'addButton' => [
                //     'name' => 'Tambah Kriteria',
                //     'url' => route('criterias.create'),
                //     'icon' => 'add',
                // ],
            ]);
    }

    function back()
    {
        return redirect()->route('dashboard');
    }


    function confirmVerify()
    {
        LivewireAlert::title('Konfirmasi')
            ->text('Apakah Anda yakin untuk mengirim data penilaian?')
            ->warning()
            ->allowOutsideClick(false)
            ->allowEscapeKey(false)
            ->confirmButtonText('Kirim')
            ->cancelButtonText('Batal')
            ->cancelButtonColor('#d33')
            ->confirmButtonColor('#3085d6')
            ->denyButtonText('Tidak')
            ->denyButtonColor('#d33')
            ->asConfirm()
            ->onConfirm('verify')
            ->show();
    }

    function verify()
    {
        DB::beginTransaction();
        try {
            foreach ($this->dataPenilaian as $key => $penilaian) {
                foreach ($penilaian['children'] as $keyChild => $child) {
                    foreach ($child['criterias'] as $keyCriteria => $criteria) {
                        $jawaban = Jawaban::where('ref_periode_id', $this->periode->id)
                            ->where('criteria_id', $criteria['criteria_id'])
                            ->where('instance_id', $this->instance)
                            ->first();
                        if (!$jawaban) {
                            LivewireAlert::title('Gagal')
                                ->text('Jawaban tidak ditemukan untuk kriteria: ' . $criteria['nama'])
                                ->error()
                                ->show();
                            return;
                        }
                        $jawaban->is_verified = true;
                        $jawaban->save();
                    }
                }
            }

            DB::commit();
            LivewireAlert::title('Berhasil')
                ->text('Data penilaian berhasil diverifikasi.')
                ->success()
                ->show();
            $this->_getDataQuestionaries();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal')
                ->text('Terjadi kesalahan saat melakukan verifikasi: ' . $e->getMessage())
                ->error()
                ->show();
            return;
        }
    }
}
