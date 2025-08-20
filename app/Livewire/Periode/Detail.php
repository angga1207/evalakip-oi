<?php

namespace App\Livewire\Periode;

use App\Models\References\Periode;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class Detail extends Component
{
    public $id, $data, $isCreate = true;

    function mount($id = null)
    {
        if ($id) {
            $this->isCreate = false;
            // Load the existing Periode data here
            $this->id = $id;
            $data = Periode::findOrFail($id);
            $this->data = $data->toArray();
            $this->data['is_active'] = $data->is_active ? 1 : 0; // Convert boolean to integer for form compatibility
        } else {
            $this->isCreate = true;
            $data = new Periode();
            $this->data = $data->toArray();
            $this->data['tanggal_mulai'] = '';
            $this->data['tanggal_selesai'] = '';
            $this->data['is_active'] = 1; // Default value for is_active
        }
    }

    public function render()
    {
        return view('livewire.periode.detail')
            ->layout('components.layouts.app', [
                'title' => $this->isCreate ? 'Tambah Periode' : 'Edit Periode',
                'breadcrumbs' => [
                    ['name' => 'Periode', 'url' => route('periode.index')],
                    ['name' => $this->isCreate ? 'Tambah Periode' : 'Edit Periode', 'url' => '#'],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('periode.index'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }

    function save()
    {
        if ($this->isCreate) {
            $this->validate([
                'data.label' => 'required|string|max:255',
                'data.tanggal_mulai' => 'required|date',
                'data.tanggal_selesai' => 'required|date|after_or_equal:data.tanggal_mulai',
                'data.is_active' => 'boolean',
            ]);

            $data = new Periode();
            $data->label = $this->data['label'];
            $data->tanggal_mulai = $this->data['tanggal_mulai'];
            $data->tanggal_selesai = $this->data['tanggal_selesai'];
            $data->is_active = $this->data['is_active'] ?? false;
            $data->save();

            Periode::where('id', '!=', $data->id)->update(['is_active' => false]);

            LivewireAlert::title('Berhasil')
                ->text('Periode berhasil ditambahkan.')
                ->success()
                ->show();
            return redirect()->route('periode.index');
        } else {
            $this->validate([
                'data.label' => 'required|string|max:255',
                'data.tanggal_mulai' => 'required|date',
                'data.tanggal_selesai' => 'required|date|after_or_equal:data.tanggal_mulai',
                'data.is_active' => 'boolean',
            ]);

            $data = Periode::findOrFail($this->id);
            $data->label = $this->data['label'];
            $data->tanggal_mulai = $this->data['tanggal_mulai'];
            $data->tanggal_selesai = $this->data['tanggal_selesai'];
            $data->is_active = $this->data['is_active'] ?? false;
            $data->save();

            if ($this->data['is_active'] == 1) {
                Periode::where('id', '!=', $data->id)->update(['is_active' => false]);
            } else {
                Periode::where('id', '!=', 0)->update(['is_active' => false]);
                Periode::where('id', '!=', $data->id)
                    ->latest('label')
                    ->first()
                    ->update(['is_active' => true]);
            }

            LivewireAlert::title('Berhasil')
                ->text('Periode berhasil diperbarui.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
            // return redirect()->route('periode.index');
        }
    }
}
