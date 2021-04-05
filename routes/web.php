<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'home');

Auth::routes([
    'reset' => false,
    'register' => false,
    'verify' => false,
    'confirm' => false,
]);

Route::middleware('auth')->group(function () {
    Route::prefix('pengguna')->group(function () {
        Route::get('/', 'UserController@index')->name('user.index')->middleware('can:viewAny,App\User');
        Route::get('/tambah', 'UserController@create')->name('user.create')->middleware('can:create,App\User');
        Route::post('/', 'UserController@store')->name('user.store')->middleware('can:create,App\User');
        Route::get('/{user}', 'UserController@edit')->name('user.edit')->middleware('can:update,user');
        Route::patch('/{user}', 'UserController@update')->name('user.update')->middleware('can:update,user');
        Route::delete('/{user}', 'UserController@destroy')->name('user.destroy')->middleware('can:delete,user');
    });

    Route::prefix('dosen')->group(function () {
        Route::get('/', 'LecturerController@index')->name('lecturer.index')->middleware('can:viewAny,App\Lecturer');
        Route::get('/tambah',
            'LecturerController@create')->name('lecturer.create')->middleware('can:create,App\Lecturer');
        Route::post('/', 'LecturerController@store')->name('lecturer.store')->middleware('can:create,App\Lecturer');
        Route::get('/{lecturer}', 'LecturerController@edit')->name('lecturer.edit')->middleware('can:update,lecturer');
        Route::patch('/{lecturer}',
            'LecturerController@update')->name('lecturer.update')->middleware('can:update,lecturer');
        Route::delete('/{lecturer}',
            'LecturerController@destroy')->name('lecturer.destroy')->middleware('can:delete,lecturer');
    });

    Route::prefix('kegiatan')->group(function () {
        Route::get('/', 'ActivityController@index')->name('activity.index')->middleware('can:viewAny,App\Activity');
        Route::get('/tambah',
            'ActivityController@create')->name('activity.create')->middleware('can:create,App\Activity');
        Route::post('/', 'ActivityController@store')->name('activity.store')->middleware('can:create,App\Activity');
        Route::get('/{activity}', 'ActivityController@edit')->name('activity.edit')->middleware('can:update,activity');
        Route::patch('/{activity}',
            'ActivityController@update')->name('activity.update')->middleware('can:update,activity');
        Route::delete('/{activity}',
            'ActivityController@destroy')->name('activity.destroy')->middleware('can:delete,activity');
    });

    Route::prefix('peserta')->group(function () {
        Route::get('/',
            'ParticipantController@index')->name('participant.index')->middleware('can:viewAny,App\Participant');
        Route::get('/tambah',
            'ParticipantController@create')->name('participant.create')->middleware('can:create,App\Participant');
        Route::post('/',
            'ParticipantController@store')->name('participant.store')->middleware('can:create,App\Participant');
        Route::get('/{participant}',
            'ParticipantController@edit')->name('participant.edit')->middleware('can:update,participant');
        Route::patch('/{participant}',
            'ParticipantController@update')->name('participant.update')->middleware('can:update,participant');
        Route::delete('/{participant}',
            'ParticipantController@destroy')->name('participant.destroy')->middleware('can:delete,participant');
    });

    Route::prefix('biaya')->group(function () {
        Route::get('/', 'FinancialController@index')->name('financial.index')->middleware('can:viewAny,App\Financial');
        Route::get('/tambah',
            'FinancialController@create')->name('financial.create')->middleware('can:create,App\Financial');
        Route::post('/', 'FinancialController@store')->name('financial.store')->middleware('can:create,App\Financial');
        Route::get('/{financial}',
            'FinancialController@edit')->name('financial.edit')->middleware('can:update,financial');
        Route::patch('/{financial}',
            'FinancialController@update')->name('financial.update')->middleware('can:update,financial');
        Route::delete('/{financial}',
            'FinancialController@destroy')->name('financial.destroy')->middleware('can:delete,financial');
    });

    Route::prefix('lampiran')->group(function () {
        Route::get('/',
            'AttachmentController@index')->name('attachment.index')->middleware('can:viewAny,App\Attachment');
        Route::get('/tambah',
            'AttachmentController@create')->name('attachment.create')->middleware('can:create,App\Attachment');
        Route::post('/',
            'AttachmentController@store')->name('attachment.store')->middleware('can:create,App\Attachment');
        Route::get('/{attachment}',
            'AttachmentController@edit')->name('attachment.edit')->middleware('can:update,attachment');
        Route::patch('/{attachment}',
            'AttachmentController@update')->name('attachment.update')->middleware('can:update,attachment');
        Route::delete('/{attachment}',
            'AttachmentController@destroy')->name('attachment.destroy')->middleware('can:delete,attachment');
    });

    Route::prefix('fakultas')->group(function () {
        Route::get('/', 'FacultyController@index')->name('faculty.index')->middleware('can:viewAny,App\Faculty');
        Route::get('/tambah', 'FacultyController@create')->name('faculty.create')->middleware('can:create,App\Faculty');
        Route::post('/', 'FacultyController@store')->name('faculty.store')->middleware('can:create,App\Faculty');
        Route::get('/{faculty}', 'FacultyController@edit')->name('faculty.edit')->middleware('can:update,faculty');
        Route::patch('/{faculty}',
            'FacultyController@update')->name('faculty.update')->middleware('can:update,faculty');
        Route::delete('/{faculty}',
            'FacultyController@destroy')->name('faculty.destroy')->middleware('can:delete,faculty');
    });

    Route::prefix('prodi')->group(function () {
        Route::get('/', 'StudyController@index')->name('study.index')->middleware('can:viewAny,App\Study');
        Route::get('/tambah', 'StudyController@create')->name('study.create')->middleware('can:create,App\Study');
        Route::post('/', 'StudyController@store')->name('study.store')->middleware('can:create,App\Study');
        Route::get('/{study}', 'StudyController@edit')->name('study.edit')->middleware('can:update,study');
        Route::patch('/{study}', 'StudyController@update')->name('study.update')->middleware('can:update,study');
        Route::delete('/{study}', 'StudyController@destroy')->name('study.destroy')->middleware('can:delete,study');
    });

    Route::prefix('kategori')->group(function () {
        Route::get('/', 'CategoryController@index')
            ->name('category.index')
            ->middleware('can:viewAny,App\Category');
        Route::get('/tambah', 'CategoryController@create')
            ->name('category.create')
            ->middleware('can:create,App\Category');
        Route::post('/', 'CategoryController@store')
            ->name('category.store')
            ->middleware('can:create,App\Category');
        Route::get('/{category}', 'CategoryController@edit')
            ->name('category.edit')
            ->middleware('can:update,category');
        Route::patch('/{category}', 'CategoryController@update')
            ->name('category.update')
            ->middleware('can:update,category');
        Route::delete('/{category}', 'CategoryController@destroy')
            ->name('category.destroy')
            ->middleware('can:delete,category');
    });

    Route::prefix('pengajuan')->group(function () {
        Route::get('/', 'SubmissionController@index')
            ->name('submission.index')
            ->middleware('can:viewAny,App\Submission');
        Route::get('/tambah', 'SubmissionController@create')
            ->name('submission.create')
            ->middleware('can:create,App\Submission');
        Route::post('/', 'SubmissionController@store')
            ->name('submission.store')
            ->middleware('can:create,App\Submission');
        Route::get('/{submission}/edit', 'SubmissionController@edit')
            ->name('submission.edit')
            ->middleware('can:update,submission');
        Route::patch('/{submission}', 'SubmissionController@update')
            ->name('submission.update')
            ->middleware('can:update,submission');
        Route::get('/{submission}/lampiran/{attachmentSubmission:name}', 'SubmissionController@attachment')
            ->name('submission.attachment');
        Route::delete('/{submission}', 'SubmissionController@destroy')
            ->name('submission.destroy')
            ->middleware('can:delete,submission');
        Route::patch('/{submission}/authorize', 'SubmissionController@authorizeSubmission')
            ->name('submission.authorize')
            ->middleware('can:authorize,submission');
        Route::patch('/{submission}/authorizeCoDean', 'SubmissionController@authorizeCoDean')
            ->name('submission.authorize.co.dean')
            ->middleware('can:authorizeCoDean,submission');
        Route::patch('/{submission}/approve', 'SubmissionController@approve')
            ->name('submission.approve')
            ->middleware('can:approve,submission');
        Route::patch('/{submission}/approveCoDean', 'SubmissionController@approveCoDean')
            ->name('submission.approve.co.dean')
            ->middleware('can:approveCoDean,submission');
        Route::patch('/{submission}/revisionCoDean', 'SubmissionController@revisionCoDean')
            ->name('submission.revision.co.dean')
            ->middleware('can:revisionCoDean,submission');
        Route::patch('/{submission}/rejectCoDean', 'SubmissionController@rejectCoDean')
            ->name('submission.reject.co.dean')
            ->middleware('can:rejectCoDean,submission');
        Route::patch('/{submission}/reject', 'SubmissionController@reject')
            ->name('submission.reject')
            ->middleware('can:reject,submission');
        Route::get('/{submission}', 'SubmissionController@show')
            ->name('submission.show')
            ->middleware('can:view,submission');
        Route::get('/{submission}/pdf', 'SubmissionController@pdf')
            ->name('submission.pdf')
            ->middleware('can:view,submission');
    });

    Route::prefix('laporan')->group(function () {
        Route::get('/unit', 'ReportController@unit')->name('report.unit');
        Route::get('/personal', 'ReportController@personal')->name('report.personal');
        Route::get('/kegiatan', 'ReportController@activity')->name('report.activity');
        Route::get('/rekap-aktivitas', 'ReportController@recapActivity')->name('report.recap.activity');
        Route::get('/rekap-dana', 'ReportController@recapFund')->name('report.recap.fund');
    });
});
