<?php

namespace App\Livewire\Grade;

use App\Models\Data\Grade;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class Detail extends Component
{
    public $id, $data, $isCreate = true;

    function mount($id = null)
    {
        if ($id) {
            $this->isCreate = false;
            $this->data = Grade::findOrFail($id);
            $this->data = $this->data->toArray();
            $this->id = $id;
        } else {
            $this->data = new Grade();
            $this->data = $this->data->toArray();
        }
        // dd($this->instance);
    }

    public function render()
    {
        return view('livewire.grade.detail')
            ->layout('components.layouts.app', [
                'title' => $this->isCreate ? 'Tambah Grade' : 'Edit Grade',
                'breadcrumbs' => [
                    ['name' => 'Grade', 'url' => route('grades.index')],
                    ['name' => $this->isCreate ? 'Tambah Grade' : 'Edit Grade', 'url' => '#'],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('grades.index'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }

    function save()
    {
        if ($this->isCreate) {
            $this->validate([
                'data.predikat' => 'required|string|max:255',
                'data.nilai' => 'required|numeric|min:0|max:100',
                'data.keterangan' => 'nullable|string|max:255',
            ]);
            Grade::create($this->data);

            return redirect()->route('grades.index')->with('success', 'Grade berhasil ditambahkan.');
        } else {
            $this->validate([
                'data.predikat' => 'required|string|max:255',
                'data.nilai' => 'required|numeric|min:0|max:100',
                'data.keterangan' => 'nullable|string|max:255',
            ]);
            $grade = Grade::findOrFail($this->id);
            $grade->update($this->data);

            LivewireAlert::title('Berhasil')
                ->success()
                ->text('Grade berhasil diperbarui.')
                ->toast()
                ->position('top-end')
                ->show();
        }
    }
}
