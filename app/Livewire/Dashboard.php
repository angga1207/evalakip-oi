<?php

namespace App\Livewire;

use App\Models\Data\Grade;
use App\Models\Data\Jawaban;
use App\Models\References\Instance;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $user = null, $roleId = null, $instanceId = null;
    public $search = '';

    function mount()
    {
        $this->user = auth()->user();
        $this->roleId = $this->user->role_id;
        $this->instanceId = $this->user->instance_id;
    }

    public function render()
    {
        if (in_array($this->roleId, [1])) {
            $arrInstance = Instance::search($this->search)
                ->orderBy('unit_id', 'asc')
                ->get();

            $totalSkor = 0;
            $instanceCount = 0;
            foreach ($arrInstance as $instance) {
                $totalSkor += $instance->GetSkor() ?? 0;
                if ($instance->GetSkor() !== null) {
                    $instanceCount++;
                }
            }
            if ($instanceCount > 0) {
                $totalSkor = $totalSkor / ($instanceCount);
            }

            $grade = Grade::where('nilai', '>=', $totalSkor)
                ->orderBy('nilai', 'asc')
                ->first();

            return view('livewire.dashboard', [
                'arrInstance' => $arrInstance,
                'totalSkor' => $totalSkor,
                'grade' => $grade,
                'instanceCount' => $instanceCount,
            ])
                ->layout('components.layouts.app', [
                    'title' => 'Dashboard Admin',
                    'breadcrumbs' => [
                        ['name' => 'Dashboard', 'url' => '#'],
                        ['name' => 'Dashboard Admin', 'url' => route('dashboard')],
                    ]
                ]);
        }

        if (in_array($this->roleId, [3])) {
            $myInstanceIds = $this->user->instances->pluck('id')->toArray();
            $arrInstance = Instance::search($this->search)
                ->whereIn('id', $myInstanceIds)
                ->orderBy('unit_id', 'asc')
                ->get();

            $totalSkor = 0;
            $instanceCount = 0;
            foreach ($arrInstance as $instance) {
                $totalSkor += $instance->GetSkor() ?? 0;
                if ($instance->GetSkor() !== null) {
                    $instanceCount++;
                }
            }
            $totalSkor = $totalSkor / ($instanceCount ?: 1);

            $grade = Grade::where('nilai', '>=', $totalSkor)
                ->orderBy('nilai', 'asc')
                ->first();

            return view('livewire.dashboard', [
                'arrInstance' => $arrInstance,
                'totalSkor' => $totalSkor,
                'grade' => $grade,
                'instanceCount' => $instanceCount,
            ])
                ->layout('components.layouts.app', [
                    'title' => 'Dashboard Admin',
                    'breadcrumbs' => [
                        ['name' => 'Dashboard', 'url' => '#'],
                        ['name' => 'Dashboard Admin', 'url' => route('dashboard')],
                    ]
                ]);
        }

        if (in_array($this->roleId, [2, 4])) {
            $jawabans = Jawaban::where('user_id', $this->user->id)
                ->where('instance_id', $this->instanceId)
                ->where('is_active', true)
                ->get();
            // $skor = $jawabans
            //     ->where('is_submitted', true)
            //     ->where('is_verified', true)
            //     ->sum('skor');
            $skor = auth()->user()->instance->GetSkor() ?? 0;
            $grade = Grade::where('nilai', '>=', $skor)
                ->orderBy('nilai', 'asc')
                ->first();

            $listGrade = Grade::orderBy('nilai', 'desc')
                ->get();

            $isSubmitted = $jawabans->where('is_submitted', true)
                ->count() > 0;
            $submittedAt = $jawabans->where('is_submitted', true)
                ->first()->updated_at ?? null;

            $evaluators = User::where('role_id', 3)
                ->whereHas('Instances', function ($query) {
                    $query->where('id', $this->instanceId);
                })
                ->get();
            $users = User::whereIn('role_id', [2, 4])
                ->where('instance_id', $this->instanceId)
                ->latest('role_id')
                ->get();

            return view('livewire.dashboard-2', [
                'skor' => $skor,
                'grade' => $grade,
                'listGrade' => $listGrade,
                'users' => $users,
                'evaluators' => $evaluators,
                'isSubmitted' => $isSubmitted,
                'submittedAt' => $submittedAt,
            ])
                ->layout('components.layouts.app', [
                    'title' => 'Dashboard Penilai',
                    'breadcrumbs' => [
                        ['name' => 'Dashboard', 'url' => '#'],
                        ['name' => 'Dashboard Penilai', 'url' => route('dashboard')],
                    ]
                ]);
        }
    }
}
