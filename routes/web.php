<?php

use App\Events\ContractSignedEvent;
use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Milestones\Milestone;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\SystemNotifications\Contract\SystemNotificationContractSigned;
use Illuminate\Database\Eloquent\Builder;
Route::get('fcm', function () {
    return view('fcm');
});

Route::get('/login', function () {
    $user = \App\Models\User::where('email', 'tom@erenovate.com')->first();

    return response()->redirectTo('/')->cookie(env('AUTH_SESSION_NAME'), $user['userId'], null, null, false, false);
});

Route::get(env('API_TOKEN') . '/{id}', function ($id) {

    $project = GuaranteeProject::query()
        ->whereHas('homeowner', function (Builder $query) {
            $query->where('userId', Auth::id());
        })
        ->orWhereHas('contractor', function (Builder $query) {
            $query->where('userId', Auth::id());
        })
        ->whereHas('contract', function (Builder $query) {
            $query->where('status', \App\Travel\Contracts\Contract::STATUS_PENDING);
            $query->whereHas('last_draft', function(Builder $query){
                $query->where('status', '!=', ContractDraft::STATUS_REJECTED);
            });
        })
        ->where('id', $id)
        ->first();

    if ($project) {

        $project->contract->last_draft->milestones[0]->update(['status' => Milestone::STATUS_IN_PROGRESS]);
        $project->contract->update(['status' => Contract::STATUS_SIGNED]);

        event(new ContractSignedEvent($project));
        SystemNotificationContractSigned::doNotification($project->contract);

        return redirect('/messages/' . $id);
    }

    return redirect('/projects');
});


Route::get('/sign-in', 'SpaController@index')->name('sign_in');
Route::get('/sign-up', 'SpaController@index')->name('sign_up');

Route::middleware(['auth'])->group(function () {
    Route::get('/{any}', 'SpaController@index')->where('any', '.*');

});

