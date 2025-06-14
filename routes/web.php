<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PustakawanController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\QnAController;

Route::middleware(['web'])->group(function () {

    Route::get('/', [Controller::class, 'index'])->name('home');
    // Route::get('/chat/messages', [ChatController::class, 'index']); // Ambil semua chat terbaru
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [LoginController::class, 'register'])->name('register');
    Route::post('actionregister', [LoginController::class, 'actionregister'])->name('actionregister');

    Route::group(['middleware' => ['auth', 'role:Mahasiswa']], function () {
        // Login as student
        Route::get('/studentdashboard', [Controller::class, 'studentdashboard'])->name('studentdashboard');
        Route::get('/internship', [InternshipController::class, 'internship'])->name('internship');
        Route::post('/internship/{id}', [InternshipController::class, 'internshipapply'])->name('internshipapply');
        Route::put('/applications/{id}', [InternshipController::class, 'update'])->name('applications.update');
        Route::post('/applications/add', [InternshipController::class, 'store'])->name('applications.store');
        Route::get('/internship/{id}/document', [Controller::class, 'internshipdocument'])->name('internshipdocument');
        Route::get('/history', [InternshipController::class, 'history'])->name('history');
        Route::post('/history/{id}/delete', [InternshipController::class, 'deleteHistory'])->name('deleteHistory');
        Route::post('/history/{id}/submit-feedback', [InternshipController::class, 'submitHistoryFeedback'])->name('submitHistoryFeedback');
        Route::post('/history/{id}/upload-kp-book', [InternshipController::class, 'uploadKpBook'])->name('uploadKpBook');
        Route::get('/maps', [Controller::class, 'maps'])->name('maps');
        Route::get('/profile', [InternshipController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [InternshipController::class, 'updateProfile'])->name('profile.update');
        // Route::post('/profile/submit-feedback', [InternshipController::class, 'submitInternshipFeedback'])->name('profile.submitInternshipFeedback');
        Route::get('/our-story', [InternshipController::class, 'internexperience'])->name('internexperience');


        //chat

        // Route::post('/chat/send', [ChatController::class, 'store']); // Kirim chat baru

        Route::prefix('qna')->middleware('auth')->group(function () {
            Route::get('/', [QnAController::class, 'index'])->name('qna.index');
            Route::get('/buat', [QnAController::class, 'create'])->name('qna.create');
            Route::post('/buat', [QnAController::class, 'store'])->name('qna.store');
            Route::get('/{question}', [QnAController::class, 'show'])->name('qna.show');
            Route::post('/{question}/jawab', [QnAController::class, 'answer'])->name('qna.answer');
            Route::get('/pertanyaan/search', [QnAController::class, 'search'])->name('questions.search');
        });
    });



    Route::group(['middleware' => ['auth', 'role:Perusahaan']], function () {
        //login as company
        //menampilkan form untuk pengisian data perusahaan
        Route::get('/company', [CompanyController::class, 'companydashboard'])->name('companydashboard');
        //menyimpan data perusahaan
        Route::post('/company/save', [CompanyController::class, 'actionsavecompany'])->name('actionsavecompany');
        //membuat lowongan magang
        Route::get('/company/vacancies', [VacancyController::class, 'companyinternship'])->name('vacancy.index');
        Route::post('/company/vacancies/store', [VacancyController::class, 'store'])->name('vacancy.store');
        Route::delete('/company/vacancies/{id}', [VacancyController::class, 'destroy'])->name('vacancy.delete');

        //mengambil data mahasiswa pelamar magang
        Route::get('/company/applications', [ApplicationsController::class, 'internapplications'])->name('applications.index');
        Route::post('/company/applications/{id}/approve', [ApplicationsController::class, 'approve'])->name('applications.approve');
        Route::post('/company/applications/{id}/reject', [ApplicationsController::class, 'reject'])->name('applications.reject');

        //monitor
        Route::get('/company/monitor', [VacancyController::class, 'monitor'])->name('vacancymonitor');

    });


    Route::group(['middleware' => ['auth', 'role:Dosen']], function () {
        //login as lecturer
        Route::get('/lecturerdashboard', [Controller::class, 'lecturerdashboard'])->name('lecturerdashboard');
    });


    Route::group(['middleware' => ['auth', 'role:Admin']], function () {
        //login as admin
        Route::get('/admindashboard', [AdminController::class, 'admindashboard'])->name('admindashboard');
        Route::get('/admin/detailuser/{id} ', [AdminController::class, 'detailuser'])->name('detailuser');
        Route::post('/admin/updateuser/{id}', [AdminController::class, 'updateuser'])->name('updateuser');
        Route::post('/admin/deleteuser/{id}', [Controller::class, 'deleteuser'])->name('deleteuser');

        //try
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');


        Route::get('/admin/monitoring', [AdminController::class, 'monitoringMahasiswa'])->name('admin.monitoring');
        Route::get('/admin/internships', [AdminController::class, 'rekapInternships'])->name('admin.internships');
        Route::get('/admin/internships/export', [AdminController::class, 'exportInternships'])->name('admin.internships.export');
        Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');


        Route::get('/admin/internship/{id}', [Controller::class, 'lecturerinternshipdetail'])->name('lecturerinternshipdetail');
        Route::get('admin/internship/{id}/document', [Controller::class, 'lecturerinternshipdocument'])->name('lecturerinternshipdocument');


        Route::get('/admin/jobs', [AdminController::class, 'manageJobs'])->name('admin.jobs');
        Route::put('/admin/jobs/{id}', [AdminController::class, 'updateJob'])->name('admin.jobs.update');
        Route::delete('/admin/jobs/{id}', [AdminController::class, 'deleteJob'])->name('admin.jobs.delete');

        Route::get('/admin/perusahaan', [AdminController::class, 'manageCompanies'])->name('admin.companies');
        Route::put('/admin/perusahaan/{id}', [AdminController::class, 'updateCompany'])->name('admin.companies.update');
        Route::delete('/admin/perusahaan/{id}', [AdminController::class, 'deleteCompany'])->name('admin.companies.destroy');
        Route::post('/admin/perusahaan/tambah', [AdminController::class, 'addCompany'])->name('admin.companies.store');
        Route::post('/admin/perusahaan/tambah-lowongan', [AdminController::class, 'addVacancy'])->name('admin.companies.store-vacancy');
        Route::put('/admin/perusahaan/update-lowongan/{id}', [AdminController::class, 'updateVacancy'])->name('admin.vacancies.update');
        Route::delete('/admin/perusahaan/hapus-lowongan/{id}', [AdminController::class, 'deleteVacancy'])->name('admin.vacancies.destroy');
    });

    Route::group(['middleware' => ['auth', 'role:Pustakawan']], function () {
        Route::get('/pustakawan', [PustakawanController::class, 'pustakawan'])->name('pustakawan');
        Route::get('/pustakawan/before', [PustakawanController::class, 'before'])->name('pustakawan.before');
        Route::get('/pustakawan/after', [PustakawanController::class, 'after'])->name('pustakawan.after');
        Route::put('/pustakawan/update/{id}', [PustakawanController::class, 'update'])->name('pustakawan.update');
    });
});
