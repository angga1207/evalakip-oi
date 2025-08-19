<?php

namespace App\Livewire\Penilaian;

use Livewire\Component;
use App\Models\Data\Jawaban;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use App\Models\References\Instance;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Components\Component as ModelComponent;

class Detail extends Component
{
    #[Url(null, true)]
    public $instance;

    use WithFileUploads;
    public $periode = null;
    public $dataQuestionaries = [];
    public $dataPenilaian = [];
    public $totalSkor = 0, $totalBobot = 0;
    public $isSubmitted = false;

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
        $user = auth()->user();
        // $user->instance_id = $this->instance;
        if ($user->instance_id == null) {
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
                ->where('instance_id', $user->instance_id)
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
                    ->where('instance_id', $user->instance_id)
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
                        ->where('instance_id', $user->instance_id)
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
        return view('livewire.penilaian.detail')
            ->layout('components.layouts.app', [
                'title' => 'Penilaian',
                'breadcrumbs' => [
                    ['name' => 'Input', 'url' => '#'],
                    ['name' => 'Penilaian', 'url' => route('penilaian')],
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

    function save()
    {
        // dd($this->totalSkor, $this->totalBobot);
        // dd($this->dataPenilaian);
        $user = auth()->user();
        DB::beginTransaction();
        try {
            foreach ($this->dataPenilaian as $key => $penilaian) {
                foreach ($penilaian['children'] as $keyChild => $child) {
                    foreach ($child['criterias'] as $keyCriteria => $criteria) {
                        // dd($criteria);
                        $jawaban = Jawaban::where('ref_periode_id', $this->periode->id)
                            ->where('criteria_id', $criteria['criteria_id'])
                            ->where('instance_id', $user->instance_id)
                            ->first();
                        if (!$jawaban) {
                            $jawaban = new Jawaban();
                        }
                        $jawaban->ref_periode_id = $this->periode->id;
                        $jawaban->criteria_id = $criteria['criteria_id'];
                        $jawaban->ref_jawaban_id = $criteria['ref_jawaban_id'];
                        $jawaban->user_id = $user->id;
                        $jawaban->evaluator_id = null;
                        $jawaban->skor = $criteria['jawaban'] ?? 0;
                        $jawaban->catatan = $criteria['catatan'] ?? null;
                        // $jawaban->evidence = $criteria['evidence'] ?? null;

                        // dd($criteria['new_evidence']->getClientOriginalName());
                        if ($criteria['new_evidence']) {
                            $fileName = str()->uuid() . '.' . $criteria['new_evidence']->extension();
                            // $fileName = str()->uuid() . $criteria['new_evidence']->getClientOriginalName();
                            // $upload = $criteria['new_evidence']->storeAs('public/evidences', $fileName, 'public');
                            // $jawaban->evidence = 'storage/public/evidences/' . $fileName;

                            $jawaban->evidence = $this->_UploadEvidenceToDrive($criteria['new_evidence'], $user);
                            // if (!$jawaban->evidence) {
                            //     LivewireAlert::title('Gagal')
                            //         ->text('Gagal mengunggah bukti: ' . $criteria['new_evidence']->getClientOriginalName())
                            //         ->error()
                            //         ->show();
                            //     return;
                            // }
                        }

                        $jawaban->instance_id = $user->instance_id;
                        $jawaban->is_active = true;
                        $jawaban->is_submitted = false;
                        $jawaban->is_verified = false;
                        $jawaban->save();
                    }
                }
            }

            Instance::where('id', $user->instance_id)
                ->update(['skor' => $this->totalSkor]);
            DB::table('instance_skor')
                ->updateOrInsert(
                    ['periode_id' => $this->periode->id, 'instance_id' => $user->instance_id],
                    ['skor' => $this->totalSkor]
                );

            DB::commit();
            LivewireAlert::title('Berhasil')
                ->text('Data penilaian berhasil disimpan.')
                ->success()
                ->show();

            $this->_getDataQuestionaries();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            LivewireAlert::title('Gagal')
                ->text('Terjadi kesalahan saat menyimpan data penilaian: ' . $e->getMessage())
                ->error()
                ->show();
            return;
        }
    }

    function confirmSubmit()
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
            ->onConfirm('submit')
            ->show();
    }

    function submit()
    {
        $user = auth()->user();
        DB::beginTransaction();
        try {
            foreach ($this->dataPenilaian as $key => $penilaian) {
                foreach ($penilaian['children'] as $keyChild => $child) {
                    foreach ($child['criterias'] as $keyCriteria => $criteria) {
                        $jawaban = Jawaban::where('ref_periode_id', $this->periode->id)
                            ->where('criteria_id', $criteria['criteria_id'])
                            ->where('instance_id', auth()->user()->instance_id)
                            ->first();
                        if (!$jawaban) {
                            LivewireAlert::title('Gagal')
                                ->text('Jawaban tidak ditemukan untuk kriteria: ' . $criteria['nama'])
                                ->error()
                                ->show();
                            return;
                        }
                        $jawaban->is_submitted = true;
                        $jawaban->save();
                    }
                }
            }

            Instance::where('id', $user->instance_id)
                ->update(['skor' => $this->totalSkor]);
            DB::table('instance_skor')
                ->updateOrInsert(
                    ['periode_id' => $this->periode->id, 'instance_id' => $user->instance_id],
                    ['skor' => $this->totalSkor]
                );

            DB::commit();
            LivewireAlert::title('Berhasil')
                ->text('Data penilaian berhasil dikirim.')
                ->success()
                ->show();
            $this->_getDataQuestionaries();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal')
                ->text('Terjadi kesalahan saat mengirim data penilaian: ' . $e->getMessage())
                ->error()
                ->show();
            return;
        }
    }

    function confirmDeleteEvidence($key, $keySub, $keyKriteria)
    {
        LivewireAlert::title('Konfirmasi')
            ->text('Apakah Anda yakin untuk menghapus bukti ini?')
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
            ->onConfirm('deleteEvidence', ['key' => $key, 'keySub' => $keySub, 'keyKriteria' => $keyKriteria])
            ->show();
    }

    function deleteEvidence($data)
    {
        // Logic to delete evidence
        // dd($data);
        $criteria = $this->dataPenilaian[$data['key']]['children'][$data['keySub']]['criterias'][$data['keyKriteria']];
        $jawaban = Jawaban::where('ref_periode_id', $this->periode->id)
            ->where('criteria_id', $criteria['criteria_id'])
            ->where('instance_id', auth()->user()->instance_id)
            ->first();
        if ($jawaban) {
            $jawaban->evidence = null;
            $jawaban->save();
            $this->dataPenilaian[$data['key']]['children'][$data['keySub']]['criterias'][$data['keyKriteria']]['evidence'] = null;
            LivewireAlert::title('Berhasil')
                ->text('Bukti berhasil dihapus.')
                ->success()
                ->show();
        } else {
            LivewireAlert::title('Gagal')
                ->text('Bukti tidak ditemukan.')
                ->error()
                ->show();
        }
    }

    function _UploadEvidenceToDrive($file, $user)
    {
        // $uri = 'https://filemanager.in/api/evalakip/Upload';
        $uri = 'https://drive-backend.oganilirkab.go.id/api/evalakip/Upload';

        if ($file) {
            $upload = Http::withHeaders([
                'api-key' => 'evalakip-52412-key',
            ])
                ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post($uri, [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'instance_name' => $user->Instance->name,
                    'instance_alias' => $user->Instance->alias,
                ]);

            if ($upload->status() === 200) {
                $response = json_decode($upload->body(), true);
                // return 'http://localhost:3000/sharer?_id=' . $response['data']['slug'];
                return 'https://drive.oganilirkab.go.id/sharer?_id=' . $response['data']['slug'];
            }
        }
        return null;
    }
}
