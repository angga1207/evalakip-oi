<?php

namespace App\Livewire\Criteria;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Data\Kriteria;
use App\Models\References\Answer;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Components\Component as ModelComponent;

class Detail extends Component
{
    #[Url(null, true)]
    public $component_id = null;
    public $id, $data, $isCreate = true;
    public $periode = null;
    public $parentComponentId = null;

    function mount($id = null)
    {
        $this->periode = Periode::where('is_active', true)->first();
        if (!$this->periode) {
            abort(404, 'Periode aktif tidak ditemukan. Silakan buat periode terlebih dahulu.');
        }

        if ($id) {
            $this->isCreate = false;
            $this->data = Kriteria::findOrFail($id);
            $this->data = $this->data->toArray();
            $this->id = $id;
            $this->component_id = $this->data['component_id'];
            $this->parentComponentId = ModelComponent::find($this->component_id)->parent_id;
            $this->data['ref_periode_id'] = $this->periode->id;
            $this->data['is_active'] = $this->data['is_active'] ? 1 : 0;
        } else {
            $this->data = new Kriteria();
            $this->data->ref_periode_id = $this->periode->id;
            $this->data->component_id = $this->component_id;
            $this->data->is_active = 1;
            $this->data = $this->data->toArray();

            if ($this->component_id) {
                $this->data['component_id'] = $this->component_id;
                $this->parentComponentId = ModelComponent::find($this->component_id)->parent_id;
            } else {
                $this->data['component_id'] = null;
                $this->parentComponentId = null;
            }
        }
        // dd($this->data);
    }

    public function render()
    {
        $parents = collect();
        $subComponents = collect();
        $answers = collect();
        if ($this->periode) {
            $parents = ModelComponent::where('ref_periode_id', $this->periode->id)
                ->whereNull('parent_id')
                ->get();
            if ($this->parentComponentId) {
                $subComponents = ModelComponent::where('ref_periode_id', $this->periode->id)
                    ->where('parent_id', $this->parentComponentId)
                    ->get();
            }
            $answers = Answer::get();
        }


        return view('livewire.criteria.detail', [
            'parents' => $parents,
            'subComponents' => $subComponents,
            'answers' => $answers,
        ])
            ->layout('components.layouts.app', [
                'title' => $this->isCreate ? 'Tambah Kriteria' : 'Edit Kriteria',
                'breadcrumbs' => [
                    ['name' => 'Kriteria', 'url' => route('criterias.index')],
                    ['name' => $this->isCreate ? 'Tambah Kriteria' : 'Edit Kriteria', 'url' => '#'],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('criterias.index'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }

    function updated($field)
    {
        if ($field === 'parentComponentId') {
            $this->data['component_id'] = null;
        }
    }

    function save()
    {
        // dd($this->data);
        if ($this->isCreate) {
            $this->validate([
                'data.component_id' => 'required|exists:components,id',
                'data.nama' => 'required|string|max:255',
                'data.penjelasan' => 'nullable|string',
                'data.ref_jawaban_id' => 'required|exists:ref_jawaban,id',
                'data.bobot' => 'nullable|numeric|min:0|max:100',
            ]);

            DB::beginTransaction();
            try {
                $this->data['ref_periode_id'] = $this->periode->id;
                $this->data['bobot'] = $this->data['bobot'] ?? 0;
                Kriteria::create($this->data);
                DB::commit();
                LivewireAlert::title('Berhasil menyimpan data kriteria.')
                    ->text('Data kriteria berhasil disimpan.')
                    ->position('center')
                    ->success()
                    ->show();
                return redirect()->route('criterias.index');
            } catch (\Exception $e) {
                DB::rollBack();
                LivewireAlert::title('Gagal menyimpan data kriteria.')
                    // ->text('Terjadi kesalahan saat menyimpan data kriteria.')
                    ->text($e->getMessage())
                    ->position('center')
                    ->error()
                    ->show();
                return;
            }
        } else {
            $this->validate([
                'data.component_id' => 'required|exists:components,id',
                'data.nama' => 'required|string|max:255',
                'data.penjelasan' => 'nullable|string',
                'data.ref_jawaban_id' => 'required|exists:ref_jawaban,id',
                'data.bobot' => 'nullable|numeric|min:0|max:100',
            ]);

            DB::beginTransaction();
            try {
                $this->data['ref_periode_id'] = $this->periode->id;
                $this->data['bobot'] = $this->data['bobot'] ?? 0;
                $kriteria = Kriteria::findOrFail($this->id);
                $kriteria->update($this->data);
                DB::commit();
                LivewireAlert::title('Berhasil memperbarui data kriteria.')
                    ->text('Data kriteria berhasil diperbarui.')
                    ->position('center')
                    ->success()
                    ->show();
                // return redirect()->route('criterias.index');
            } catch (\Exception $e) {
                DB::rollBack();
                LivewireAlert::title('Gagal memperbarui data kriteria.')
                    // ->text('Terjadi kesalahan saat memperbarui data kriteria.')
                    ->text($e->getMessage())
                    ->position('center')
                    ->error()
                    ->show();
                return;
            }
        }
    }
}
