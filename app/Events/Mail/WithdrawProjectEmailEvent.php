<?php

namespace App\Events\Mail;

use App\Travel\Users\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class WithdrawProjectEmailEvent implements ShouldQueue
{
    use SerializesModels;

    public $user;
    public $project_name;

    public function __construct(User $user, $project_name)
    {
        $this->user = $user;
        $this->project_name = $project_name;
    }
}
