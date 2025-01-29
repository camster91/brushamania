<?php

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

use App\Http\Controllers\QueryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('metalogs')->group(function () {
    Route::get('/table/{secretKey}/{modalName}', [QueryController::class, 'metaTables']);
    Route::get('/{secretKey}/{log}', [QueryController::class, 'logs']);
});

Auth::routes();

Route::get('/test-cert', function() {
	return view('pdf.certificate');
});
Route::get('/school-export/{year}', 'SchoolController@exportSchool');
Route::get('/school-master-list', 'SchoolController@exportMasterList');

Route::get('/parent-login', 'CustomLoginController@loginForm');
Route::post('/parent-login', 'CustomLoginController@login');
Route::get('/school-registration', 'SchoolController@create');
Route::get('/dentist-registration', 'DentistController@create');
Route::get('/rotarian-registration', 'RotarianController@create');
Route::get('/parent-registration', 'ParentController@create');
// Route::get('/politician-registration', 'PoliticianController@create');

Route::post('/school', 'SchoolController@store');
Route::post('/dentist', 'DentistController@store');
Route::post('/rotarian', 'RotarianController@store');
Route::post('/parent', 'ParentController@store');
Route::post('/politician', 'PoliticianController@store');


Route::get('/check-registration-closure', 'SchoolController@checkClosureDate');

Route::get('/presentation/schools/{assigned}', 'PresentationController@getWithNoAssigned');
Route::get('/school/registration/all', 'SchoolController@listAll');
Route::get('/school/view/{school}', 'SchoolController@publicView');
Route::get('/school/autofill/{city}/{school}', 'SchoolController@autofill');
Route::get('/dentist/view/{dentist}', 'DentistController@publicView');
Route::get('/rotarian/view/{rotarian}', 'RotarianController@publicView');


Route::group(['middleware' => ['web', 'auth']], function() {
	Route::post('/image', 'ImageHandlerController@store');

	Route::post('/user', 'UserController@store');
	Route::get('/users/admin/list', 'UserController@getAdminList');
	Route::get('/user/role', 'UserController@getRole');
	Route::get('/user/profile', 'UserController@getUserProfile');
	Route::post('/user/password', 'UserController@updatePassword');
	Route::post('/user/password/initial', 'UserController@updateInitialPassword');
	Route::delete('/user/{id}', 'UserController@destroy');
	Route::patch('/user/profile/{id}', 'UserController@update');
	Route::get('/user/url-slug', 'UserController@getUserSlug');

	Route::get('/year/active', 'YearController@getYear');
	Route::get('/year/registered', 'YearController@getRegisteredCount');
	Route::get('/year/brush-tracker/{id}', 'YearController@isBrushTrackingActive');

	Route::post('/certificate', 'BrushTrackerController@sendCert');
	Route::post('/certificate-all', 'BrushTrackerController@sendCertAll');
	Route::get('/children/export/{year}', 'ChildrenController@export');
	Route::get('/children/brushes', 'BrushTrackerController@getList');
	Route::post('/children/brushes', 'BrushTrackerController@store');
	Route::patch('/children/brushes/{id}', 'BrushTrackerController@update');
	Route::delete('/children/brushes/delete/{child_id}', 'BrushTrackerController@resetDays');
	Route::get('/brushtracker/days', 'BrushTrackerController@getDays');
	Route::get('/brushtrackers/complete', 'BrushTrackerController@getComplete');


	Route::get('/school/presentation/{school}/{year?}', 'PresentationController@show');
	Route::patch('/school/presentation/{id}', 'PresentationController@update');
	Route::get('/presentations/list', 'PresentationController@getList');

	Route::post('/school-unregistered', 'SchoolController@storeUnregistered');
	Route::get('/schools/export/{year}', 'SchoolController@export');
	Route::get('/school/edit/{school}', 'SchoolController@edit');
	Route::get('/school/{school}/{year?}', 'SchoolController@show');
	Route::patch('/school/{id}', 'SchoolController@update');
	Route::delete('/school/{id}', 'SchoolController@destroy');
	Route::get('/schools/list/{year?}', 'SchoolController@getList');
	Route::post('/schools/import', 'SchoolController@importMasterList');

	Route::get('/dentists/export/{year}', 'DentistController@export');
	Route::post('/dentists/import', 'DentistController@import');
	Route::get('/dentist/{dentist}', 'DentistController@show');
	Route::get('/dentist/edit/{dentist}', 'DentistController@edit');
	Route::patch('/dentist/{id}', 'DentistController@update');
	Route::delete('/dentist/{id}', 'DentistController@destroy');
	Route::get('/dentists/registered', 'DentistController@getRegistered');
	Route::get('/dentists/list/{year?}', 'DentistController@getList');

	Route::get('/rotarians/export/{year}', 'RotarianController@export');
	Route::get('/rotarian/{rotarian}', 'RotarianController@show');
	Route::get('/rotarian/edit/{rotarian}', 'RotarianController@edit');
	Route::patch('/rotarian/{id}', 'RotarianController@update');
	Route::delete('/rotarian/{id}', 'RotarianController@destroy');
	Route::get('/rotarians/registered', 'RotarianController@getRegistered');
	Route::get('/rotarians/list/{year?}', 'RotarianController@getList');

	Route::get('/politicians/export', 'PoliticianController@export');
	Route::post('/politicians/import', 'PoliticianController@import');
	Route::get('/politician/list', 'PoliticianController@getList');
	Route::get('/politician/{politician}', 'PoliticianController@show');
	Route::get('/politician/edit/{politician}', 'PoliticianController@edit');
	Route::patch('/politician/{id}', 'PoliticianController@update');
	Route::delete('/politician/{id}', 'PoliticianController@destroy');

	Route::get('/parents/export/{year}', 'ParentController@export');
	Route::get('/parent/{parent}', 'ParentController@show');
	Route::get('/parent/edit/{parent}', 'ParentController@edit');
	Route::patch('/parent/{id}', 'ParentController@update');
	Route::delete('/parent/{id}', 'ParentController@destroy');
	Route::get('/parents/list/{year?}', 'ParentController@getList');

	Route::get('/emails/list/{receiver}/{address?}', 'UserController@getMailList');
	Route::post('/mailblast', 'MailBlastController@store');
	Route::post('/mailblast/save-recipients', 'MailBlastController@saveRecipient');
	Route::post('/mailblast/send', 'MailBlastController@sendMail');
	Route::patch('/mailblast/{id}', 'MailBlastController@update');
	Route::delete('/mailblast/{id}', 'MailBlastController@destroy');
	Route::get('/mailblast/list', 'MailBlastController@getList');

	Route::patch('/autoreply-emails/{id}', 'AutoreplyEmailController@update');
	Route::get('/autoreply-emails/list', 'AutoreplyEmailController@getList');
	Route::post('/mail/test', 'AutoreplyEmailController@testMail');

	Route::post('/child', 'ChildrenController@store');
	Route::get('/child/{child}', 'ChildrenController@show');
	Route::get('/child/edit/{child}', 'ChildrenController@edit');
	Route::patch('/child/{id}', 'ChildrenController@update');
	Route::delete('/child/{id}', 'ChildrenController@destroy');
	Route::get('/children/list/{year?}', 'ChildrenController@getList');
	Route::get('/parents/children', 'ChildrenController@getChildrenOfParents');

	Route::get('/', 'HomeController@index')->name('home2');
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/schools', 'HomeController@index')->name('schools');
	Route::get('/schools/new', 'HomeController@index')->name('schools_new');
	Route::get('/schools/{school}/{year?}', 'HomeController@index')->name('school');
	Route::get('/schools/presentation/{school}/{year?}', 'HomeController@index')->name('presentation');
	Route::get('/dentists', 'HomeController@index')->name('dentists');
	Route::get('/dentists/new', 'HomeController@index')->name('dentists_new');
	Route::get('/dentists/{dentist}', 'HomeController@index')->name('dentist');
	Route::get('/rotarians', 'HomeController@index')->name('rotarians');
	Route::get('/rotarians/new', 'HomeController@index')->name('rotarians_new');
	Route::get('/rotarians/{rotarian}', 'HomeController@index')->name('rotarian');
	Route::get('/parents', 'HomeController@index')->name('parents');
	Route::get('/parents/new', 'HomeController@index')->name('parents_new');
	Route::get('/parents/{parent}', 'HomeController@index')->name('parent');
	Route::get('/parents/add-children/{parent}', 'HomeController@index')->name('parent-child');
	Route::get('/children', 'HomeController@index')->name('children');
	Route::get('/politicians', 'HomeController@index')->name('politicians');
	Route::get('/politicians/{politician}', 'HomeController@index')->name('politician');
	Route::get('/children/brushtracker/{child}', 'HomeController@index')->name('child_brushtracker');
	Route::get('/children/{child}', 'HomeController@index')->name('child');
	Route::get('/mailblast', 'HomeController@index')->name('mailblast');
	Route::get('/mailblast/new', 'HomeController@index')->name('new_mailblast');
	Route::get('/mailblast/update/{mailblast}', 'HomeController@index')->name('update_mailblast');
	Route::get('/autoreply-emails', 'HomeController@index')->name('autoreply');
	Route::get('/autoreply-emails/update/{autoreply}', 'HomeController@index')->name('autoreply');
	Route::get('/brush-tracker', 'HomeController@index')->name('brushtracker');
	Route::get('/profile', 'HomeController@index')->name('profile');
	Route::get('/users/admin', 'HomeController@index')->name('users');
	Route::get('/my-children', 'HomeController@index')->name('my_children');
	Route::get('/add-children', 'HomeController@index')->name('add_children');
	Route::get('/{path}', function() {
		return redirect()->route('home');
	});
	Route::get('/mailblast/{mailblast}', 'MailBlastController@show');
	Route::get('/autoreply-emails/{autoreply}', 'AutoreplyEmailController@show');
});