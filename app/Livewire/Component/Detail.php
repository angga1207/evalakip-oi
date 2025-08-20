<?php

namespace App\Livewire\Component;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\References\Periode;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Components\Component as ModelComponent;

class Detail extends Component
{
    #[Url(null, true)]
    public $parent_id;
    public $id, $data, $isCreate = true;
    public $periode = null;

    function mount($id = null)
    {
        $this->periode = Periode::where('is_active', true)->first();
        if (!$this->periode) {
            abort(404, 'Periode aktif tidak ditemukan. Silakan buat periode terlebih dahulu.');
        }

        if ($id) {
            $this->isCreate = false;
            $this->data = ModelComponent::findOrFail($id);
            $this->data = $this->data->toArray();
            $this->id = $id;
        } else {
            $this->data = new ModelComponent();
            $this->data->ref_periode_id = $this->periode->id;
            $this->data->parent_id = null;
            $this->data->is_active = true;
            $this->data = $this->data->toArray();
        }

        if (!$id) {
            if ($this->parent_id) {
                $this->data['parent_id'] = $this->parent_id;
            } else {
                $this->data['parent_id'] = null;
            }
        }
    }

    public function render()
    {
        $parents = ModelComponent::where('ref_periode_id', $this->periode->id)
            ->whereNull('parent_id')
            ->orderBy('nama')
            ->get();
        $this->data['ref_periode_id'] = $this->periode->id;

        return view('livewire.component.detail', [
            'parents' => $parents,
        ])
            ->layout('components.layouts.app', [
                'title' => $this->isCreate ? 'Tambah Komponen' : 'Edit Komponen',
                'breadcrumbs' => [
                    ['name' => 'Komponen', 'url' => route('components.index')],
                    ['name' => $this->isCreate ? 'Tambah Komponen' : 'Edit Komponen', 'url' => '#'],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('components.index'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }

    function save()
    {
        if ($this->isCreate) {
            $this->validate([
                'data.nama' => 'required|string|max:255',
                'data.parent_id' => 'nullable|exists:components,id',
            ]);

            DB::beginTransaction();
            try {
                $data = new ModelComponent();
                $data->ref_periode_id = $this->periode->id;
                if ($this->data['parent_id'] == null) {
                    $data->parent_id = null;
                } else {
                    $data->parent_id = $this->data['parent_id'];
                }
                $data->nama = $this->data['nama'];
                $data->bobot = $this->data['bobot'];
                $data->save();

                DB::commit();
                LivewireAlert::title('Success!')
                    ->text('Komponen berhasil disimpan.')
                    ->success()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return redirect()->route('components.index');
            } catch (\Throwable $e) {
                DB::rollBack();
                LivewireAlert::title('Error!')
                    ->text('Terjadi kesalahan saat menyimpan komponen: ' . $e->getMessage())
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return;
            }
        } else {
            $this->validate([
                'data.nama' => 'required|string|max:255',
                'data.parent_id' => 'nullable|exists:components,id',
            ]);

            DB::beginTransaction();
            try {
                $data = ModelComponent::findOrFail($this->id);
                $data->nama = $this->data['nama'];
                $data->bobot = $this->data['bobot'];
                if ($this->data['parent_id'] == null) {
                    $data->parent_id = null;
                } else {
                    $data->parent_id = $this->data['parent_id'];
                }
                $data->save();

                DB::commit();
                LivewireAlert::title('Success!')
                    ->text('Komponen berhasil diperbarui.')
                    ->success()
                    ->toast()
                    ->position('top-end')
                    ->show();
                $this->mount($this->id); // Refresh the component data
                // return redirect()->route('components.index');
            } catch (\Throwable $e) {
                DB::rollBack();
                LivewireAlert::title('Error!')
                    ->text('Terjadi kesalahan saat memperbarui komponen: ' . $e->getMessage())
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return;
            }
        }
    }
}
