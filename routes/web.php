<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
Route::get('/logout', App\Livewire\Auth\Logout::class)->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/', App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/profile', App\Livewire\User\Me::class)->name('profile');

    Route::middleware(['InstanceRoutes'])->group(function () {
        // Penilaian
        Route::get('/penilaian', App\Livewire\Penilaian\Detail::class)->name('penilaian');
    });

    Route::middleware(['EvaluatorRoutes'])->group(function () {
        // Evaluasi
        Route::get('/evaluasi', App\Livewire\Evaluasi\Detail::class)->name('evaluasi');
        Route::get('/recap', App\Livewire\Recap\Detail::class)->name('recap');
    });

    Route::middleware(['SuperAdminRoutes'])->group(function () {
        // Reference routes
        Route::get('/instansi', App\Livewire\Instance\Index::class)->name('instansi.index');
        Route::get('/instansi/create', App\Livewire\Instance\Detail::class)->name('instansi.create');
        Route::get('/instansi/edit/{id}', App\Livewire\Instance\Detail::class)->name('instansi.edit');

        Route::get('/periode', App\Livewire\Periode\Index::class)->name('periode.index');
        Route::get('/periode/create', App\Livewire\Periode\Detail::class)->name('periode.create');
        Route::get('/periode/edit/{id}', App\Livewire\Periode\Detail::class)->name('periode.edit');

        Route::get('/answers', App\Livewire\Answers\Index::class)->name('answers.index');
        Route::get('/answers/create', App\Livewire\Answers\Detail::class)->name('answers.create');
        Route::get('/answers/edit/{id}', App\Livewire\Answers\Detail::class)->name('answers.edit');

        // Components
        Route::get('/components', App\Livewire\Component\Index::class)->name('components.index');
        Route::get('/components/create', App\Livewire\Component\Detail::class)->name('components.create');
        Route::get('/components/edit/{id}', App\Livewire\Component\Detail::class)->name('components.edit');

        // Criteria
        Route::get('/criterias', App\Livewire\Criteria\Index::class)->name('criterias.index');
        Route::get('/criterias/create', App\Livewire\Criteria\Detail::class)->name('criterias.create');
        Route::get('/criterias/edit/{id}', App\Livewire\Criteria\Detail::class)->name('criterias.edit');

        // Grades
        Route::get('/grades', App\Livewire\Grade\Index::class)->name('grades.index');
        Route::get('/grades/create', App\Livewire\Grade\Detail::class)->name('grades.create');
        Route::get('/grades/edit/{id}', App\Livewire\Grade\Detail::class)->name('grades.edit');

        // Import
        Route::get('/import', App\Livewire\Import\Detail::class)->name('import');

        // Users
        Route::get('/users', App\Livewire\User\Index::class)->name('users.index');
        Route::get('/users/create', App\Livewire\User\Detail::class)->name('users.create');
        Route::get('/users/edit/{id}', App\Livewire\User\Detail::class)->name('users.edit');
    });
});

Route::impersonate();
