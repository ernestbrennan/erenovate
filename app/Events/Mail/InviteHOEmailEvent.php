<?php

namespace App\Events\Mail;

use App\Travel\Projects\GuaranteeProject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class InviteHOEmailEvent implements ShouldQueue
{
    use SerializesModels;

    public $guarantee_project;
    public $homeowner_recently_created;

    public function __construct(GuaranteeProject $guarantee_project, bool $homeowner_recently_created)
    {
        $this->guarantee_project = $guarantee_project;
        $this->homeowner_recently_created = $homeowner_recently_created;
    }
}
