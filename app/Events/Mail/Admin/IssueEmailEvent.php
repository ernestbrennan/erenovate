<?php

namespace App\Events\Mail\Admin;

use App\Travel\Issues\Issue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class IssueEmailEvent implements ShouldQueue
{
    use SerializesModels;

    public $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }
}
