<?php

namespace App\Events\Mail;

use App\Travel\Projects\GuaranteeProject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class PayFeeEmailEvent implements ShouldQueue
{
    use SerializesModels;

    public $guarantee_project;
    public $is_extra_fee;

    public function __construct(GuaranteeProject $guarantee_project, $is_extra_fee = false)
    {
        $this->guarantee_project = $guarantee_project;
        $this->is_extra_fee = $is_extra_fee;
    }
}
