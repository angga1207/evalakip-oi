<?php

namespace App\Livewire\Instance;

use App\Models\References\Instance;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $datas = Instance::search()
            ->orderBy('name')
            ->get();
        return view('livewire.instance.index', [
            'datas' => $datas,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Instansi',
                'breadcrumbs' => [
                    ['name' => 'Referensi', 'url' => '#'],
                    ['name' => 'Instansi', 'url' => route('instansi.index')],
                ],
                // 'addButton' => [
                //     'name' => 'Tambah Instansi',
                //     'url' => route('instansi.create'),
                //     'icon' => 'add',
                // ],
            ]);
    }

    function delete($id)
    {
        // dd($id);
        // $data = Instance::findOrFail($id);
        // $data->delete();
        // session()->flash('success', 'Instansi berhasil dihapus.');
    }
}
