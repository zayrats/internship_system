<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\Logincontroller;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['web'])->group(function () {

    Route::get('/', [Controller::class, 'index'])->name('home');
    Route::get('/login', [Logincontroller::class, 'login'])->name('login');
    Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [Logincontroller::class, 'register'])->name('register');
    Route::post('actionregister', [LoginController::class, 'actionregister'])->name('actionregister');

    Route::group(['middleware' => ['auth', 'role:Mahasiswa']], function () {
        // Login as student
        Route::get('/studentdashboard', [Controller::class, 'studentdashboard'])->name('studentdashboard');
        Route::get('/internship', [InternshipController::class, 'internship'])->name('internship');
        Route::post('/internship/{id}', [InternshipController::class, 'internshipapply'])->name('internshipapply');
        Route::get('/internship/{id}/document', [Controller::class, 'internshipdocument'])->name('internshipdocument');
        Route::get('/history', [InternshipController::class, 'history'])->name('history');
        Route::get('/maps', [Controller::class, 'maps'])->name('maps');
        Route::get('/profile', [InternshipController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [InternshipController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/submit-feedback', [InternshipController::class, 'submitInternshipFeedback'])->name('profile.submitInternshipFeedback');
        Route::post('/profile/upload-kp', [InternshipController::class, 'uploadKpBook'])->name('profile.uploadKpBook');
        Route::get('/our-story', [InternshipController::class, 'internexperience'])->name('internexperience');
    });



    Route::group(['middleware' => ['auth', 'role:Perusahaan']], function () {
        //login as company
        //menampilkan form untuk pengisian data perusahaan
        Route::get('/company', [Controller::class, 'companydashboard'])->name('companydashboard');
        //menyimpan data perusahaan
        Route::post('actionsavecompany', [Controller::class, 'actionsavecompany'])->name('actionsavecompany');
        //membuat lowongan magang
        Route::get('/company/companyinternship', [Controller::class, 'companyinternship'])->name('companyinternship');
        //membuka lowongan magang
        Route::post('actionopeninternship', [Controller::class, 'actionopeninternship'])->name('actionopeninternship');
        //mengambil data mahasiswa pelamar magang
        Route::get('/company/getstudent', [Controller::class, 'getstudent'])->name('getstudent');
        //menyeleksi mahasiswa
        Route::post('actionselectstudent', [Controller::class, 'actionselectstudent'])->name('actionselectstudent');
    });


    Route::group(['middleware' => ['auth', 'role:Dosen']], function () {
        //login as lecturer
        Route::get('/lecturerdashboard', [Controller::class, 'lecturerdashboard'])->name('lecturerdashboard');
        Route::get('/lecturerinternship', [Controller::class, 'lecturerinternship'])->name('lecturerinternship');
        Route::get('/lecturerinternship/{id}', [Controller::class, 'lecturerinternshipdetail'])->name('lecturerinternshipdetail');
        Route::get('/lecturerinternship/{id}/document', [Controller::class, 'lecturerinternshipdocument'])->name('lecturerinternshipdocument');
    });


    Route::group(['middleware' => ['auth', 'role:Admin']], function () {
        //login as admin
        Route::get('/admindashboard', [Controller::class, 'admindashboard'])->name('admindashboard');
        Route::get('/detailuser/{id} ', [Controller::class, 'detailuser'])->name('detailuser');
        Route::post('updateuser/{id}', [Controller::class, 'updateuser'])->name('updateuser');
        Route::post('deleteuser/{id}', [Controller::class, 'deleteuser'])->name('deleteuser');
    });
});
