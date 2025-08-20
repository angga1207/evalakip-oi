<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models\References\Instance;

class Index extends Component
{
    public function render()
    {
        $datas = Instance::search()
            ->when(auth()->user()->role_id === 3, function ($q) {
                return $q->whereIn('id', auth()->user()->instances->pluck('id')->toArray());
            })
            ->orderBy('skor', 'desc')
            ->get();

        return view('livewire.report.index', [
            'datas' => $datas,
        ])
            ->layout('components.layouts.app', [
                'title' => 'Laporan Evalakip',
                'breadcrumbs' => [
                    ['name' => 'Report', 'url' => '#'],
                    ['name' => 'Laporan', 'url' => route('report.index')],
                ],
                // 'addButton' => [
                //     'name' => 'Tambah Instansi',
                //     'url' => route('instansi.create'),
                //     'icon' => 'add',
                // ],
            ]);
    }
}
