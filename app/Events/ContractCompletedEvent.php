<?php

namespace App\Events;

use App\Travel\Projects\GuaranteeProject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class ContractCompletedEvent implements ShouldQueue
{
    use SerializesModels;

    public $guarantee_project;

    public function __construct(GuaranteeProject $guarantee_project)
    {
        $this->guarantee_project = $guarantee_project;
    }
}
