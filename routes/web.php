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

use App\Http\Controllers\ChartController;

Route::get('listing-eropa', ['as' => 'listing-eropa', 'uses' => 'VisitorController@visitorListPublic']);
Route::get('gatelist-eropa', ['as' => 'gatelist-eropa', 'uses' => 'GateController@historyList']);
Route::get('intruderlist-eropa', ['as' => 'intruderlist-eropa', 'uses' => 'IntruderCountingController@historyList']);
Route::get('speedlist-eropa', ['as' => 'speedlist-eropa', 'uses' => 'HistorySpeedController@historyList']);

Route::get('coming-soon', 'MaintenanceController@comingSoon')->name('coming.soon.index');
Route::get('404', 'MaintenanceController@notFound')->name('not-found.index');

Auth::routes();

Route::post('test-speech', 'MaintenanceController@testSpeech')->name('test.speech');
Route::get('test-notification', 'MaintenanceController@testNotification')->name('test.notification');

Route::group(['as' => 'admin.'], function () {
    Route::group(['middleware' => ['auth:web', 'role.admin']], function () {
        Route::get('/', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

        Route::resource('user', 'UserController', ['except' => ['show', 'destroy']]);
        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::post('list', ['as' => 'list', 'uses' => 'UserController@userList']);
            Route::post('delete', ['as' => 'delete', 'uses' => 'UserController@delete']);
        });

        Route::resource('employee', 'EmployeeController', ['except' => ['show', 'destroy']]);
        Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
            Route::post('list', ['as' => 'list', 'uses' => 'EmployeeController@employeeList']);
            Route::post('delete', ['as' => 'delete', 'uses' => 'EmployeeController@delete']);
        });

        Route::resource('camera', 'CameraController', ['except' => ['show', 'destroy', 'create']]);
        Route::group(['prefix' => 'camera', 'as' => 'camera.'], function () {
            Route::get('create/{type}', ['as' => 'create', 'uses' => 'CameraController@create']);
            Route::get('preview/{id}', ['as' => 'preview', 'uses' => 'CameraController@preview']);
            Route::post('screenshot', ['as' => 'screenshot', 'uses' => 'CameraController@screenshot']);
            Route::get('start/{id}', ['as' => 'start', 'uses' => 'CameraController@start']);
            Route::get('stop/{id}', ['as' => 'stop', 'uses' => 'CameraController@stop']);
            Route::post('list', ['as' => 'list', 'uses' => 'CameraController@cameraList']);
            Route::post('delete', ['as' => 'delete', 'uses' => 'CameraController@delete']);
            Route::post('update-line', ['as' => 'update.line', 'uses' => 'CameraController@updateLine']);
            Route::post('update-mask', ['as' => 'update.mask', 'uses' => 'CameraController@updateMask']);
            Route::patch('upgrade/{camera}', ['as' => 'upgrade', 'uses' => 'CameraController@upgrade']);
        });

        Route::resource('scheduler', 'SchedulerController', ['except' => ['show', 'destroy']]);
        Route::group(['prefix' => 'scheduler', 'as' => 'scheduler.'], function () {
            Route::post('list', ['as' => 'list', 'uses' => 'SchedulerController@schedulerList']);
            Route::get('start/{id}', ['as' => 'start', 'uses' => 'SchedulerController@startScheduler']);
            Route::get('stop/{id}', ['as' => 'stop', 'uses' => 'SchedulerController@stopScheduler']);
            Route::post('delete', ['as' => 'delete', 'uses' => 'SchedulerController@delete']);
        });

        Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'VehicleController@index']);
            Route::get('create/', ['as' => 'create', 'uses' => 'VehicleController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'VehicleController@store']);
            Route::get('edit/{vehicle}', ['as' => 'edit', 'uses' => 'VehicleController@edit']);
            Route::patch('update/{vehicle}', ['as' => 'update', 'uses' => 'VehicleController@update']);
            Route::post('list', ['as' => 'list', 'uses' => 'VehicleController@vehicleList']);
            Route::post('delete/', ['as' => 'delete', 'uses' => 'VehicleController@delete']);
        });

        Route::resource('visitor', 'VisitorController', ['except' => ['show', 'destroy']]);
        Route::group(['prefix' => 'visitor', 'as' => 'visitor.'], function () {
            Route::post('list', ['as' => 'list', 'uses' => 'VisitorController@visitorList']);
            Route::post('delete', ['as' => 'delete', 'uses' => 'VisitorController@delete']);
            Route::post('rate', ['as' => 'rate', 'uses' => 'VisitorController@visitorRate']);
            Route::get('chart', ['as' => 'chart', 'uses' => 'VisitorController@visitorChart']);
        });

        Route::group(['prefix' => 'cctv', 'as' => 'cctv.'], function () {
            Route::get('/{type}', ['as' => 'index', 'uses' => 'CctvController@index']);
            Route::get('detail/{type}/{id}', ['as' => 'detail', 'uses' => 'CctvController@detail']);
            Route::get('impression/{id}', ['as' => 'impression', 'uses' => 'CctvController@impression']);
            Route::get('tally/{id}', ['as' => 'tally', 'uses' => 'CctvController@tally']);
        });

        Route::group(['prefix' => 'attendance', 'as' => 'attendance.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'AttendanceController@index']);
            Route::post('list', ['as' => 'list', 'uses' => 'AttendanceController@attendanceList']);
        });

        Route::group(['prefix' => 'member', 'as' => 'member.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'MemberController@index']);
            Route::post('detail', ['as' => 'detail', 'uses' => 'MemberController@detail']);
            Route::post('store', ['as' => 'store', 'uses' => 'MemberController@store']);
            Route::post('update', ['as' => 'update', 'uses' => 'MemberController@updateMember']);
            Route::post('delete', ['as' => 'delete', 'uses' => 'MemberController@delete']);
            Route::post('list', ['as' => 'list', 'uses' => 'MemberController@memberList']);
            Route::get('create/{type}', ['as' => 'create', 'uses' => 'MemberController@create']);
            Route::get('capture/{id}', ['as' => 'capture', 'uses' => 'MemberController@capture']);
        });

        Route::group(['prefix' => 'gate', 'as' => 'gate.'], function () {
            Route::get('history', ['as' => 'history', 'uses' => 'GateController@indexHistory']);
            Route::post('history/list', ['as' => 'list', 'uses' => 'GateController@historyList']);
        });

        Route::group(['prefix' => 'vehicle_counting', 'as' => 'vehicle_counting.'], function () {
            Route::get('history', ['as' => 'history', 'uses' => 'VehicleCountingController@indexHistory']);
            Route::post('history/list', ['as' => 'list', 'uses' => 'VehicleCountingController@historyList']);
        });

        Route::group(['prefix' => 'people_counting', 'as' => 'people_counting.'], function () {
            Route::get('history', ['as' => 'history', 'uses' => 'PeopleCountingController@indexHistory']);
            Route::post('history/list', ['as' => 'list', 'uses' => 'PeopleCountingController@historyList']);
        });

        Route::group(['prefix' => 'intruder_counting', 'as' => 'intruder_counting.'], function () {
            Route::get('history', ['as' => 'history', 'uses' => 'IntruderCountingController@indexHistory']);
            Route::post('history/list', ['as' => 'list', 'uses' => 'IntruderCountingController@historyList']);
        });

        Route::group(['prefix' => 'speed_history', 'as' => 'speed_history.'], function () {
            Route::get('history', ['as' => 'history', 'uses' => 'HistorySpeedController@index']);
            Route::post('history/list', ['as' => 'list', 'uses' => 'HistorySpeedController@historyList']);
        });

        Route::group(['prefix' => 'chart', 'as' => 'chart.'], function () {
            Route::get('history', ['as' => 'history', 'uses' => 'PeopleController@indexPeople']);
        });

        Route::group(['prefix' => 'people', 'as' => 'people.'], function () {
            Route::get('history', ['as' => 'history', 'uses' => 'PeopleController@indexPeople']);
            Route::post('history/data', ['as' => 'data', 'uses' => 'PeopleController@data']);
        });

        Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            Route::get('visitor-counting', ['as' => 'index.visitor.counting', 'uses' => 'ReportController@indexReportVisitorCounting']);
            Route::get('visitor-download', ['as' => 'index.visitor.download', 'uses' => 'ReportController@indexReportVisitorDownload']);
            Route::post('list', ['as' => 'list', 'uses' => 'ReportController@reportVisitorCountingList']);
            Route::post('download-list', ['as' => 'list', 'uses' => 'ReportController@reportVisitorDownloadList']);
            Route::post('download-report', ['as' => 'download', 'uses' => 'ReportController@downloadReport']);
        });

        Route::group(['prefix' => 'area', 'as' => 'area.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'AreaController@index']);
            Route::get('create/', ['as' => 'create', 'uses' => 'AreaController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'AreaController@store']);
            Route::get('edit/{area}', ['as' => 'edit', 'uses' => 'AreaController@edit']);
            Route::patch('update/{area}', ['as' => 'update', 'uses' => 'AreaController@update']);
            Route::post('list', ['as' => 'list', 'uses' => 'AreaController@areaList']);
            Route::post('delete/', ['as' => 'delete', 'uses' => 'AreaController@delete']);
        });

        Route::group(['prefix' => 'cluster', 'as' => 'cluster.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'ClusterController@index']);
            Route::get('create/', ['as' => 'create', 'uses' => 'ClusterController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'ClusterController@store']);
            Route::get('edit/{cluster}', ['as' => 'edit', 'uses' => 'ClusterController@edit']);
            Route::patch('update/{cluster}', ['as' => 'update', 'uses' => 'ClusterController@update']);
            Route::post('list', ['as' => 'list', 'uses' => 'ClusterController@clusterList']);
            Route::post('delete/', ['as' => 'delete', 'uses' => 'ClusterController@delete']);
        });

        Route::group(['prefix' => 'residential-gate', 'as' => 'residential-gate.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'ResidentialGateController@index']);
            Route::get('create/', ['as' => 'create', 'uses' => 'ResidentialGateController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'ResidentialGateController@store']);
            Route::get('edit/{residentialGate}', ['as' => 'edit', 'uses' => 'ResidentialGateController@edit']);
            Route::patch('update/{residentialGate}', ['as' => 'update', 'uses' => 'ResidentialGateController@update']);
            Route::post('list', ['as' => 'list', 'uses' => 'ResidentialGateController@residentialGateList']);
            Route::post('delete/', ['as' => 'delete', 'uses' => 'ResidentialGateController@delete']);
        });

        Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'NotificationController@index']);
            Route::post('list', ['as' => 'list', 'uses' => 'NotificationController@notificationList']);
        });

        Route::group(['prefix' => 'camera-management', 'as' => 'camera-management.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'CameraManagementController@index']);
            Route::post('list', ['as' => 'list', 'uses' => 'CameraManagementController@List']);
        });
    });
});
