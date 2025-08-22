<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\References\Periode;
use App\Models\References\Instance;
use App\Models\Components\Component as ModelComponent;
use App\Models\Data\Jawaban;

class Detail extends Component
{
    #[Url(null, true)]
    public $id;
    public $instance, $periode;

    function mount($id)
    {
        $this->periode = Periode::where('is_active', true)->first();
        if (!$this->periode) {
            abort(404, 'Periode aktif tidak ditemukan. Silakan buat periode terlebih dahulu.');
        }
        $this->id = $id;
        $this->instance = Instance::findOrFail($id);
    }

    public function render()
    {
        $datas = [];
        $komponents = ModelComponent::whereNull('parent_id')
            ->where('ref_periode_id', $this->periode->id)
            ->where('bobot', '!=', 100)
            ->get();

        foreach ($komponents as $komponent) {
            $nilai1 = 0;
            $nilai2 = 0;
            $nilai3 = 0;

            $criteriaIds1 = $komponent->Children->first()->Criterias ? $komponent->Children->first()->Criterias->where('is_active', true)->pluck('id')->toArray() : [];
            $criteriaIds2 = $komponent->Children->skip(1)->first()->Criterias ? $komponent->Children->skip(1)->first()->Criterias->where('is_active', true)->pluck('id')->toArray() : [];
            $criteriaIds3 = $komponent->Children->skip(2)->first()->Criterias ? $komponent->Children->skip(2)->first()->Criterias->where('is_active', true)->pluck('id')->toArray() : [];

            $jawaban1 = Jawaban::where('ref_periode_id', $this->periode->id)
                ->where('instance_id', $this->instance->id)
                ->whereIn('criteria_id', $criteriaIds1)
                ->get();
            $average1 = $jawaban1->sum('skor') / count($criteriaIds1);
            $nilai1 = $average1 * ($komponent->Children->first()->bobot ?? 1);

            $jawaban2 = Jawaban::where('ref_periode_id', $this->periode->id)
                ->where('instance_id', $this->instance->id)
                ->whereIn('criteria_id', $criteriaIds2)
                ->get();
            $average2 = $jawaban2->sum('skor') / count($criteriaIds2);
            $nilai2 = $average2 * ($komponent->Children->skip(1)->first()->bobot ?? 1);

            $jawaban3 = Jawaban::where('ref_periode_id', $this->periode->id)
                ->where('instance_id', $this->instance->id)
                ->whereIn('criteria_id', $criteriaIds3)
                ->get();
            $average3 = $jawaban3->sum('skor') / count($criteriaIds3);
            $nilai3 = $average3 * ($komponent->Children->skip(2)->first()->bobot ?? 1);

            $datas[] = [
                'id' => $komponent->id,
                'nama' => $komponent->nama,
                'bobot' => $komponent->bobot,
                'nilai1' => $nilai1,
                'nilai2' => $nilai2,
                'nilai3' => $nilai3,
                'totalNilai' => $nilai1 + $nilai2 + $nilai3,
            ];
        }

        // dd($komponents);
        return view('livewire.report.detail', [
            'datas' => $datas
        ])
            ->layout('components.layouts.app', [
                'title' => $this->instance->name,
                'breadcrumbs' => [
                    ['name' => 'Laporan', 'url' => '#'],
                    ['name' => $this->instance->name, 'url' => '#'],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('report.index'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }
}
