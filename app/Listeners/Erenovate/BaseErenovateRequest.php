<?php

namespace App\Listeners\Erenovate;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class BaseErenovateRequest implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'erenovate';
}
