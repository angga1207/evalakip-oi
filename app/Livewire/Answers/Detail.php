<?php

namespace App\Livewire\Answers;

use Livewire\Component;
use App\Models\References\Answer;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Detail extends Component
{
    public $id, $data, $isCreate = true;
    public $valueType = 'boolean'; // Default answer type
    public $arrValues = [];

    function mount($id = null)
    {
        if ($id) {
            $this->isCreate = false;
            // Load the existing Answer data here
            $this->id = $id;
            $data = Answer::findOrFail($id);
            $this->data = $data->toArray();
            $this->valueType = null;
            if (str()->squish($this->data['label']) == 'Ya/Tidak') {
                $this->valueType = 'boolean';
            } elseif (str()->squish($this->data['label']) == 'A,B,C') {
                $this->valueType = 'abc';
            } elseif (str()->squish($this->data['label']) == 'A,B,C,D') {
                $this->valueType = 'abcd';
            } elseif (str()->squish($this->data['label']) == 'A,B,C,D,E') {
                $this->valueType = 'abcde';
            }
            $this->arrValues = [];
            $data->Values->each(function ($value) {
                $this->arrValues[] = ['id' => $value->id, 'value' => $value->nilai];
            });
        } else {
            $this->isCreate = true;
            $data = new Answer();
            $this->data = $data->toArray();
            $this->data['label'] = '';
            $this->arrValues = [];
            if ($this->valueType == 'boolean') {
                $this->arrValues = [
                    ['value' => '1'],
                    ['value' => '0'],
                ];
            } else if ($this->valueType == 'abc') {
                $this->arrValues = [
                    ['value' => '1'],
                    ['value' => '0.66'],
                    ['value' => '0.33'],
                ];
            } elseif ($this->valueType == 'abcd') {
                $this->arrValues = [
                    ['value' => '1'],
                    ['value' => '0.75'],
                    ['value' => '0.5'],
                    ['value' => '0.25'],
                ];
            } elseif ($this->valueType == 'abcde') {
                $this->arrValues = [
                    ['value' => '1'],
                    ['value' => '0.8'],
                    ['value' => '0.6'],
                    ['value' => '0.4'],
                    ['value' => '0.2'],
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.answers.detail')
            ->layout('components.layouts.app', [
                'title' => $this->isCreate ? 'Tambah Jawaban' : 'Edit Jawaban',
                'breadcrumbs' => [
                    ['name' => 'Referensi Jawaban', 'url' => route('answers.index')],
                    ['name' => $this->isCreate ? 'Tambah Jawaban' : 'Edit Jawaban', 'url' => '#'],
                ],
                'addButton' => [
                    'name' => 'Kembali',
                    'url' => route('answers.index'),
                    'icon' => 'arrow_back',
                ],
            ]);
    }

    function updated($field)
    {
        if ($field == 'valueType') {
            if ($this->valueType == 'boolean') {
                $this->arrValues = [
                    ['value' => '1'],
                    ['value' => '0'],
                ];
            } elseif ($this->valueType == 'abc') {
                $this->arrValues = [
                    ['value' => 1],
                    ['value' => 0.66],
                    ['value' => 0.33],
                ];
            } elseif ($this->valueType == 'abcd') {
                $this->arrValues = [
                    ['value' => 1],
                    ['value' => 0.75],
                    ['value' => 0.5],
                    ['value' => 0.25],
                ];
            } elseif ($this->valueType == 'abcde') {
                $this->arrValues = [
                    ['value' => 1],
                    ['value' => 0.8],
                    ['value' => 0.6],
                    ['value' => 0.4],
                    ['value' => 0.2],
                ];
            }
        }
    }

    function save()
    {
        if ($this->isCreate) {
            $this->validate([
                'data.label' => 'required|string|max:255',
                'valueType' => 'required|string|in:boolean,abc,abcd,abcde',
            ]);

            if ($this->valueType == 'boolean') {
                $this->validate([
                    'arrValues.0.value' => 'required|numeric',
                    'arrValues.1.value' => 'required|numeric',
                ]);
            } elseif ($this->valueType == 'abc') {
                $this->validate([
                    'arrValues.0.value' => 'required|numeric',
                    'arrValues.1.value' => 'required|numeric',
                    'arrValues.2.value' => 'required|numeric',
                ]);
            } elseif ($this->valueType == 'abcd') {
                $this->validate([
                    'arrValues.0.value' => 'required|numeric',
                    'arrValues.1.value' => 'required|numeric',
                    'arrValues.2.value' => 'required|numeric',
                    'arrValues.3.value' => 'required|numeric',
                ]);
            } elseif ($this->valueType == 'abcde') {
                $this->validate([
                    'arrValues.0.value' => 'required|numeric',
                    'arrValues.1.value' => 'required|numeric',
                    'arrValues.2.value' => 'required|numeric',
                    'arrValues.3.value' => 'required|numeric',
                    'arrValues.4.value' => 'required|numeric',
                ]);
            }

            $data = new Answer();
            $data->label = str()->squish($this->data['label']);
            $data->save();

            foreach ($this->arrValues as $key => $value) {
                if ($this->valueType == 'boolean') {
                    $data->Values()->create([
                        'label' =>  $key == 0 ? 'Ya' : 'Tidak',
                        'nilai' => $value['value']
                    ]);
                }
                if ($this->valueType == 'abc') {
                    $data->Values()->create([
                        'label' => chr(65 + $key), // A, B, C
                        'nilai' => $value['value']
                    ]);
                } elseif ($this->valueType == 'abcd') {
                    $data->Values()->create([
                        'label' => chr(65 + $key), // A, B, C, D
                        'nilai' => $value['value']
                    ]);
                } elseif ($this->valueType == 'abcde') {
                    $data->Values()->create([
                        'label' => chr(65 + $key), // A, B, C, D, E
                        'nilai' => $value['value']
                    ]);
                }
            }

            LivewireAlert::title('Berhasil')
                ->text('Jawaban berhasil ditambahkan.')
                ->success()
                ->show();
            return redirect()->route('answers.index');
        } else {
            $this->validate([
                'data.label' => 'required|string|max:255',
                'valueType' => 'required|string|in:boolean,abc,abcd,abcde',
            ]);

            if ($this->valueType == 'boolean') {
                $this->validate([
                    'arrValues.0.value' => 'required|numeric',
                    'arrValues.1.value' => 'required|numeric',
                ]);
            } elseif ($this->valueType == 'abc') {
                $this->validate([
                    'arrValues.0.value' => 'required|numeric',
                    'arrValues.1.value' => 'required|numeric',
                    'arrValues.2.value' => 'required|numeric',
                ]);
            } elseif ($this->valueType == 'abcd') {
                $this->validate([
                    'arrValues.0.value' => 'required|numeric',
                    'arrValues.1.value' => 'required|numeric',
                    'arrValues.2.value' => 'required|numeric',
                    'arrValues.3.value' => 'required|numeric',
                ]);
            } elseif ($this->valueType == 'abcde') {
                $this->validate([
                    'arrValues.0.value' => 'required|numeric',
                    'arrValues.1.value' => 'required|numeric',
                    'arrValues.2.value' => 'required|numeric',
                    'arrValues.3.value' => 'required|numeric',
                    'arrValues.4.value' => 'required|numeric',
                ]);
            }

            $data = Answer::findOrFail($this->id);
            $data->label = str()->squish($this->data['label']);
            $data->save();

            // dd($this->arrValues);

            foreach ($this->arrValues as $key => $value) {
                if ($this->valueType == 'boolean') {
                    $data->Values()->updateOrCreate(
                        ['id' => $value['id']],
                        ['label' =>  $key == 0 ? 'Ya' : 'Tidak', 'nilai' => $value['value']]
                    );
                } elseif ($this->valueType == 'abc') {
                    $data->Values()->updateOrCreate(
                        ['id' => $value['id']],
                        ['label' => chr(65 + $key), 'nilai' => $value['value']]
                    );
                } elseif ($this->valueType == 'abcd') {
                    $data->Values()->updateOrCreate(
                        ['id' => $value['id']],
                        ['label' => chr(65 + $key), 'nilai' => $value['value']]
                    );
                } elseif ($this->valueType == 'abcde') {
                    $data->Values()->updateOrCreate(
                        ['id' => $value['id']],
                        ['label' => chr(65 + $key), 'nilai' => $value['value']]
                    );
                }
            }

            LivewireAlert::title('Berhasil')
                ->text('Jawaban berhasil diperbarui.')
                ->success()
                ->show();
            // return redirect()->route('answers.index');
        }
    }
}
