<?php

use App\Commons\Utils;
use App\User;
use Illuminate\Support\Facades\Route;

Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->middleware('auth:api');
Route::post('register/user', 'Auth\RegisterController@registerStartupUser');
Route::post('register/investor', 'Auth\RegisterController@registerInvestor');

 //Route::get('/dashboard', 'DashboardController@getSummary');
Route::get('/test', function(){
    return 'testing';
});

// allowed for all authenticated users
Route::middleware(['auth:api','mentor.default'])->group(function () {
    Route::get('/user', 'Auth\UserController@getLoggedInUser');
    Route::post('/user', 'Auth\UserController@update');
    Route::resource('/startup', 'StartupController')->only('show', 'index');
    Route::resource('/mentor/area', 'MentorAreaController')->only('index', 'show');
    Route::resource('/solution/stages', 'SolutionStageController')->only('index', 'show');
    Route::resource('/solution/statuses', 'SolutionStatusController')->only('index', 'show');
    Route::resource('/solution', 'SolutionController')->only('show', 'index');
    Route::get('/hurdle/active/', 'HurdleController@showActive');
    Route::resource('/hurdle', 'HurdleController')->only('index', 'show');
    Route::get('/solutions/mentor/{id}', 'SolutionController@solutionsForMentor');
    Route::get('/solutions/startup/{id}', 'SolutionController@solutionsForStartup');
    Route::get('/dashboard', 'DashboardController@getSummary');
    Route::post('/user/password_reset', 'Auth\UserController@resetPassword');


    Route::apiResources([
        'files' => 'Files\FileController',
        'threads' => 'Messages\ThreadController',
        'threads/{thread}/messages' => 'Messages\MessageController',
        'assignments' => 'Assignments\AssignmentController',
        'assignments/{assignment}/submissions' => 'Assignments\AssignmentSubmissionController',
        'assignments/{assignment}/grades' => 'Assignments\AssignmentGradeController',
        'calendars' => 'Calendars\CalendarController',
        'calendars/{calendar}/events' => 'Calendars\EventController',
        'calendars/{calendar}/tasks' => 'Calendars\TaskController',
    ]);


    Route::get('contacts', 'Messages\ContactController@index');

    // authenticated users with startup role
    Route::middleware('auth.role:'.getRoles('app-config.roles.startup'))->group(function () {
        Route::resource('/solution', 'SolutionController')->except('index', 'show');
        Route::get('/solutions/active', 'SolutionController@getActiveSolution');
        Route::resource('/startup', 'StartupController')->except('index', 'show','destroy');
    });

    // authenticated users with mentor role
    Route::middleware('auth.role:'.getRoles('app-config.roles.mentor'))->group(function () {
    });

    // authenticated users with admin role
    Route::middleware('auth.role:'.getRoles('app-config.roles.admin'))->group(function () {
        Route::resource('/mentor/area', 'MentorAreaController')->except('index', 'show');
        Route::put('/mentor/assign/area/{mentor_id}', 'MentorAreaController@assignArea');
        Route::put('/solution/assign/mentor/{mentor_id}', 'SolutionController@assignMentor');
        Route::delete('/solution/assign/mentor/{mentor_id}', 'SolutionController@deleteMentor');
        Route::get('/users', 'Auth\UserController@index');
        Route::resource('/mentors', 'MentorController');
        Route::resource('/startup', 'StartupController')->only('destroy');
        Route::post('register/mentor', 'Auth\RegisterController@registerMentor');
        Route::put('/users/{user_id}', 'Auth\UserController@resetPassword');
        Route::resource('/users/startups', 'UserStartupController');
        Route::resource('/hurdle', 'HurdleController')->except('index', 'show');
    });

    // authenticated users with investor role
    Route::middleware('auth.role:'.getRoles('app-config.roles.investor'))->group(function () {
    });
});

Route::get('files/open/{file}', 'Files\FileController@open')
    ->name('open_file')
    ->middleware('signed');


function getRoles($key)
{
    return Utils::getConfig($key);
}
