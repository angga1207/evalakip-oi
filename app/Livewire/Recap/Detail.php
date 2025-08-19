<?php

namespace App\Livewire\Recap;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\References\Periode;
use App\Models\References\Instance;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Components\Component as ModelComponent;
use App\Models\Data\Jawaban;

class Detail extends Component
{
    #[Url(null, true)]
    public $instance;
    public $instanceData = null;
    public $periode = null;

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

        if ($this->instance) {
            $this->instanceData = Instance::find($this->instance);
        } else {
            abort(404, 'Instance not found');
        }
    }

    public function render()
    {
        $datas = [];
        $components = ModelComponent::where('ref_periode_id', $this->periode->id)
            ->with(['Children', 'Children.Criterias'])
            ->where('parent_id', null)
            ->get()
            ->where('bobot', '!=', 100);
        foreach ($components as $component) {
            $datas[] = [
                'component' => $component->toArray(),
                'criterias' => $component->Children->map(function ($child) {
                    return $child->Criterias->map(function ($criteria) {
                        return $criteria->toArray();
                    });
                })->toArray(),
                'jawaban' => $component->Children->map(function ($child) {
                    return $child->Criterias->map(function ($criteria) {
                        return Jawaban::where('ref_periode_id', $this->periode->id)
                            ->where('instance_id', $this->instance)
                            ->where('criteria_id', $criteria->id)
                            ->get()
                            ->toArray();
                    });
                })->toArray(),
            ];
        }

        return view('livewire.recap.detail', [
            'datas' => $datas,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Rekap',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'url' => '#'],
                    ['name' => 'Rekap', 'url' => route('recap')],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('dashboard'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }
}
