<?php
Route::middleware('admin-auth')->group(function () {
    //ProjectController
    Route::get('/project.getList', 'ProjectController@getList');

//IssueController
    Route::get('/issue.getByProject', 'IssueController@getByProject');
    Route::get('/issue.getById', 'IssueController@getById');
    Route::post('/issue.sendMessage', 'IssueController@sendMessage');
    Route::post('/issue.close', 'IssueController@close');

//ContractController
    Route::get('/contract.getSigned', 'ContractController@getSigned');

//SummaryController
    Route::get('/summary.getByProject', 'SummaryController@getByProject');
    Route::post('/summary.changeTotal', 'SummaryController@changeTotal');

});
Route::post('/login', 'AuthController@login');

Route::get('/', function () {

    return view('admin');
});
Route::get('/{any}', function () {
    return view('admin');
})->where('any', '.*');
