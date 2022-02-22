<?php

namespace App\Providers;

use App\Travel\ArchivedDrafts\Repositories\ArchivedDraftRepositoryEloquent;
use App\Travel\ArchivedDrafts\Repositories\Interfaces\ArchivedDraftRepositoryInterface;
use App\Travel\Milestones\Repositories\Interfaces\MilestoneRepositoryInterface;
use App\Travel\Milestones\Repositories\MilestoneRepositoryEloquent;
use App\Travel\Projects\Repositories\{
    Interfaces\GuaranteeProjectRepositoryInterface,
    Interfaces\ErenovateProjectRepositoryInterface,
    GuaranteeProjectRepositoryEloquent,
    ErenovateProjectRepositoryEloquent
};
use App\Travel\Chats\Repositories\{
    Interfaces\ChatRepositoryInterface,
    ChatRepositoryEloquent
};
use App\Travel\Contracts\Repositories\{
    Interfaces\ContractRepositoryInterface,
    ContractRepositoryEloquent
};
use App\Travel\Messages\Repositories\{
    Interfaces\MessageRepositoryInterface,
    MessageRepositoryEloquent
};
use App\Travel\Notes\Repositories\{
    Interfaces\NoteRepositoryInterface,
    NoteRepositoryEloquent
};

use App\Travel\Files\Repositories\{
    Interfaces\FileRepositoryInterface,
    FileRepositoryEloquent
};
use App\Travel\Issues\Repositories\{
    Interfaces\IssueRepositoryInterface,
    IssueRepositoryEloquent
};

use App\Travel\Users\Repositories\{
    Interfaces\UserRepositoryInterface,
    Interfaces\UserInfoRepositoryInterface,
    UserRepositoryEloquent,
    UserInfoRepositoryEloquent
};

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            GuaranteeProjectRepositoryInterface::class,
            GuaranteeProjectRepositoryEloquent::class
        );
        $this->app->bind(
            ErenovateProjectRepositoryInterface::class,
            ErenovateProjectRepositoryEloquent::class
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepositoryEloquent::class
        );
        $this->app->bind(
            UserInfoRepositoryInterface::class,
            UserInfoRepositoryEloquent::class
        );
        $this->app->bind(
            ChatRepositoryInterface::class,
            ChatRepositoryEloquent::class
        );
        $this->app->bind(
            MessageRepositoryInterface::class,
            MessageRepositoryEloquent::class
        );
        $this->app->bind(
            NoteRepositoryInterface::class,
            NoteRepositoryEloquent::class
        );
        $this->app->bind(
            ContractRepositoryInterface::class,
            ContractRepositoryEloquent::class
        );
        $this->app->bind(
            FileRepositoryInterface::class,
            FileRepositoryEloquent::class
        );
        $this->app->bind(
            IssueRepositoryInterface::class,
            IssueRepositoryEloquent::class
        );
        $this->app->bind(
            MilestoneRepositoryInterface::class,
            MilestoneRepositoryEloquent::class
        );
        $this->app->bind(
            ArchivedDraftRepositoryInterface::class,
            ArchivedDraftRepositoryEloquent::class
        );
    }
}