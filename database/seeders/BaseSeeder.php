<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BaseSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Unit Utama', 'slug' => 'unit-utama'],
            ['name' => 'Unit Pendukung', 'slug' => 'unit-pendukung'],
            ['name' => 'Unit Tambahan', 'slug' => 'unit-tambahan'],
            ['name' => 'Unit Kecamatan', 'slug' => 'unit-kecamatan'],
        ];

        foreach ($units as $unit) {
            if (\App\Models\References\Unit::where('slug', $unit['slug'])->exists()) {
                continue;
            }
            \App\Models\References\Unit::create($unit);
        }

        // Instance
        $uri = 'https://sicaramapis.oganilirkab.go.id/api/local/caram/realisasi/listInstance';
        $response = Http::get($uri);
        if ($response->successful()) {
            $instances = $response->json()['data'] ?? [];
            foreach ($instances as $instance) {
                if (\App\Models\References\Instance::where('code', $instance['code'])->exists()) {
                    continue;
                }
                \App\Models\References\Instance::create([
                    'id_eoffice' => $instance['id_eoffice'],
                    'unit_id' => \App\Models\References\Unit::first()->id,
                    'name' => $instance['name'],
                    'alias' => $instance['alias'],
                    'code' => $instance['code'],
                    'logo' => $instance['logo'],
                    'status' => 'active',
                    'description' => $instance['description'] ?? null,
                    'address' => $instance['address'] ?? null,
                    'phone' => $instance['phone'] ?? null,
                    'fax' => $instance['fax'] ?? null,
                    'email' => $instance['email'] ?? null,
                    'website' => $instance['website'] ?? null,
                    'facebook' => $instance['facebook'] ?? null,
                    'instagram' => $instance['instagram'] ?? null,
                    'youtube' => $instance['youtube'] ?? null,
                ]);
            }
        }

        // Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Penilai', 'slug' => 'penilai'],
            ['name' => 'Evaluator', 'slug' => 'evaluator'],
            ['name' => 'Kepala OPD', 'slug' => 'kepala-opd'],
        ];

        foreach ($roles as $role) {
            if (\App\Models\References\Role::where('slug', $role['slug'])->exists()) {
                continue;
            }
            \App\Models\References\Role::create($role);
        }

        // Users
        $users[] = [
            'name' => 'Developer',
            'username' => 'developer',
            'email' => 'developer@example.com',
            'image' => 'https://ui-avatars.com/api/?name=Developer',
            'role_id' => 1,
            'instance_id' => null,
            'jabatan' => null,
            'password' => 'arungboto',
        ];
        $users[] = [
            'name' => 'Super Admin',
            'username' => 'admin1',
            'email' => 'admin1@example.com',
            'image' => 'https://ui-avatars.com/api/?name=Super+Admin',
            'role_id' => 1,
            'instance_id' => null,
            'jabatan' => null,
            'password' => 'admin1',
        ];

        foreach ($users as $user) {
            if (\App\Models\User::where('username', $user['username'])->exists()) {
                continue;
            }
            \App\Models\User::create([
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'image' => $user['image'],
                'role_id' => $user['role_id'],
                'instance_id' => $user['instance_id'],
                'jabatan' => $user['jabatan'],
                'no_hp' => null,
                'password' => bcrypt($user['password']),
            ]);
        }

        $periode[] = [
            'label' => '2023',
            'tanggal_mulai' => '2023-01-01',
            'tanggal_selesai' => '2023-12-31',
            'is_active' => true,
        ];

        $periode[] = [
            'label' => '2024',
            'tanggal_mulai' => '2024-01-01',
            'tanggal_selesai' => '2024-12-31',
            'is_active' => true,
        ];

        $periode[] = [
            'label' => '2025',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-12-31',
            'is_active' => true,
        ];

        foreach ($periode as $p) {
            if (\App\Models\References\Periode::where('label', $p['label'])->exists()) {
                continue;
            }
            \App\Models\References\Periode::create([
                'label' => $p['label'],
                'tanggal_mulai' => $p['tanggal_mulai'],
                'tanggal_selesai' => $p['tanggal_selesai'],
                'is_active' => $p['is_active'],
            ]);
        }

        $answers = [
            [
                'label' => 'Ya/Tidak',
                'values' => [
                    ['label' => 'Ya', 'nilai' => 1],
                    ['label' => 'Tidak', 'nilai' => 0],
                ]
            ],
            [
                'label' => 'A,B,C',
                'values' => [
                    ['label' => 'A', 'nilai' => 1],
                    ['label' => 'B', 'nilai' => 0.5],
                    ['label' => 'C', 'nilai' => 0],
                ]
            ],
            [
                'label' => 'A,B,C,D',
                'values' => [
                    ['label' => 'A', 'nilai' => 1],
                    ['label' => 'B', 'nilai' => 0.66],
                    ['label' => 'C', 'nilai' => 0.33],
                    ['label' => 'D', 'nilai' => 0],
                ]
            ],
            [
                'label' => 'A,B,C,D,E',
                'values' => [
                    ['label' => 'A', 'nilai' => 1],
                    ['label' => 'B', 'nilai' => 0.75],
                    ['label' => 'C', 'nilai' => 0.5],
                    ['label' => 'D', 'nilai' => 0.25],
                    ['label' => 'E', 'nilai' => 0],
                ]
            ]
        ];

        foreach ($answers as $answer) {
            if (\App\Models\References\Answer::where('label', $answer['label'])->exists()) {
                continue;
            }
            $newAnswer = \App\Models\References\Answer::create(['label' => $answer['label']]);
            foreach ($answer['values'] as $value) {
                $newAnswer->Values()->create([
                    'label' => $value['label'],
                    'nilai' => $value['nilai'],
                ]);
            }
        }


        $grades = [
            [
                'predikat' => 'AA',
                'nilai' => 100,
                'keterangan' => 'Jika seluruh kriteria telah terpenuhi (100%) dan telah dipertahankan dalam setidaknya 5 tahun terakhir.'
            ],
            [
                'predikat' => 'A',
                'nilai' => 90,
                'keterangan' => 'Jika seluruh kriteria telah terpenuhi (100%) dan telah dipertahankan dalam setidaknya 1 tahun terakhir.'
            ],
            [
                'predikat' => 'BB',
                'nilai' => 80,
                'keterangan' => 'Jika kualitas seluruh kriteria telah terpenuhi (100%) sesuai dengan mandat kebijakan nasional.'
            ],
            [
                'predikat' => 'B',
                'nilai' => 70,
                'keterangan' => 'Jika kualitas sebagian besar kriteria telah terpenuhi (>75% - 100%).'
            ],
            [
                'predikat' => 'CC',
                'nilai' => 60,
                'keterangan' => 'Jika kualitas sebagian besar kriteria telah terpenuhi (>50% - 75%).'
            ],
            [
                'predikat' => 'C',
                'nilai' => 50,
                'keterangan' => 'Jika kualitas sebagian kecil kriteria telah terpenuhi (>25% - 50%).'
            ],
            [
                'predikat' => 'D',
                'nilai' => 30,
                'keterangan' => 'Jika kriteria penilaian akuntabilitas kinerja telah mulai dipenuhi (>0% - 25%).'
            ],
            [
                'predikat' => 'E',
                'nilai' => 0,
                'keterangan' => 'Jika sama sekali tidak ada upaya dalam pemenuhan kriteria penilaian akuntabilitas kinerja.'
            ]
        ];

        foreach ($grades as $grade) {
            if (\App\Models\Data\Grade::where('predikat', $grade['predikat'])->exists()) {
                continue;
            }
            \App\Models\Data\Grade::create($grade);
        }
    }
}
