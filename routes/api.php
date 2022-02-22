<?php

Route::post('user.sign-in', 'UserController@signIn');
Route::post('user.sign-up', 'UserController@signUp');

Route::post('file.download', 'FileController@download');

Route::middleware(['auth'])->group(function () {
//ProjectController
    Route::post('project.get-or-create', 'ProjectController@getOrCreate');
    Route::post('project.hire-from-erenovate', 'ProjectController@hireFromErenovate');
    Route::post('project.withdraw', 'ProjectController@withdraw');
    Route::post('project.invite-ho', 'ProjectController@inviteHO');
    Route::post('project.visit', 'ProjectController@visit');
    Route::get('project.check-visited', 'ProjectController@checkVisited');

//ChatController
    Route::get('/chat.by-project', 'ChatController@getByProject');

//FileController
    Route::post('file.upload', 'FileController@upload');
    Route::get('file.find', 'FileController@find');
//    Route::post('file.download', 'FileController@download');
    Route::post('file.delete', 'FileController@delete');
    Route::get('file.get-history', 'FileController@getFileHistory');

//MessageController
    Route::get('message.getList', 'MessageController@getList');
    Route::post('message.sendMessage', 'MessageController@sendMessage');
    Route::post('message.readMessage', 'MessageController@readMessage');

//ContractController
    Route::get('contract.getList', 'ContractController@getList');
    Route::post('contract.getByProject', 'ContractController@getByProject');
    Route::get('contract.getDraftHistory', 'ContractController@getDraftHistory');
    Route::post('contract.getContractSigned', 'ContractController@getContractSigned');
    Route::post('contract.confirmComplete', 'ContractController@confirmComplete');

//ContractDraftController
    Route::post('contract-draft.getCurrentDraft', 'ContractDraftController@getCurrentDraft');
    Route::post('contract-draft.getHistoryDraft', 'ContractDraftController@getHistoryDraft');
    Route::post('contract-draft.newDraft', 'ContractDraftController@newDraft');
    Route::post('contract-draft.proposeDraft', 'ContractDraftController@proposeDraft');
    Route::post('contract-draft.reject', 'ContractDraftController@reject');
    Route::post('contract-draft.accept', 'ContractDraftController@accept');
    Route::post('contract-draft.exportPdf', 'ContractDraftController@exportPdf');

//UserController
    Route::get('user.get-info', 'UserController@getinfo');
    Route::get('user.get-draft-info', 'UserController@getDraftInfo');
    Route::post('user.update-draft-info', 'UserController@updateDraftInfo');
    Route::post('user.confirm-draft-info', 'UserController@confirmInterlocutorInfo');
    Route::post('user.un-confirm-draft-info', 'UserController@unConfirmInterlocutorInfo');
    Route::post('user.logout', 'UserController@logout');
    Route::post('user.save-fcm-token', 'UserController@fcm');

//NoteController
    Route::post('note.getList', 'NoteController@getList');
    Route::post('note.sendNote', 'NoteController@sendNote');

//InvoiceController
    Route::get('invoice.getList', 'InvoiceController@getList');
    Route::get('invoice.getNew', 'InvoiceController@getNew');
    Route::get('invoice.getCurrent', 'InvoiceController@getCurrent');
    Route::get('invoice.getHistory', 'InvoiceController@getHistory');
    Route::post('invoice.create', 'InvoiceController@create');
    Route::post('invoice.reject', 'InvoiceController@reject');
    Route::post('invoice.confirm', 'InvoiceController@confirm');
    Route::post('invoice.complete', 'InvoiceController@complete');
    Route::post('invoice.unverify', 'InvoiceController@unverify');
    Route::post('invoice.exportPdf', 'InvoiceController@exportPdf');

    Route::post('invoice.getSingle', 'InvoiceController@getSingle');
    Route::post('invoice.approvePayment', 'InvoiceController@approvePayment');

//Milestone
    Route::get('milestone.get-current', 'MilestoneController@getCurrent');
    Route::get('milestone.get-by-id', 'MilestoneController@getById');
    Route::get('milestone.get-edited', 'MilestoneController@getEdited');
    Route::post('milestone.edit', 'MilestoneController@edit');
    Route::post('milestone.accept', 'MilestoneController@accept');
    Route::post('milestone.reject', 'MilestoneController@reject');

//SummaryController
    Route::get('summary.by-project', 'SummaryController@byProject');

//PaymentController
    Route::post('payment.contract-fee', 'PaymentController@contractFee');
    Route::post('payment.summary-fee', 'PaymentController@summaryFee');

//IssueController
    Route::post('issue.create', 'IssueController@create');
    Route::post('issue.priceModification', 'IssueController@priceModification');
    Route::post('issue.close', 'IssueController@close');
    Route::get('issue.getById', 'IssueController@getById');
    Route::post('issue.sendMessage', 'IssueController@sendMessage');

//SettingController
    Route::get('setting.get', 'SettingController@get');
    Route::post('setting.change-password', 'SettingController@changePassword');
    Route::post('setting.save', 'SettingController@save');
});

//ArchivedDraftsController
    Route::get('archived-draft.list', 'ArchivedDraftController@getList');
    Route::get('archived-draft.get-by-id', 'ArchivedDraftController@getById');
    Route::post('archived-draft.save', 'ArchivedDraftController@save');
    Route::post('archived-draft.update', 'ArchivedDraftController@update');
    Route::post('archived-draft.delete', 'ArchivedDraftController@delete');

