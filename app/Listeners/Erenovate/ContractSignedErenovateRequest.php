<?php

namespace App\Listeners\Erenovate;

use App\Events\ContractSignedEvent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ContractSignedErenovateRequest extends BaseErenovateRequest
{
    public function handle(ContractSignedEvent $event)
    {
        $guarantee_project = $event->guarantee_project;

        $client = new Client();

        try {
            $response = $client->post('https://erenovate.com/homeowners/api/guarantee/updateProjectStatus', [
                'headers' => [
                    "Content-Type" => "application/json",
                    "Apikey"  => "9yhG68695Tf731Uj09Klmjj9421KnQsaJJH"
                ],
                'json' => [
                    "ProjectID" => $guarantee_project->project_id,
                    "Status" => "In Progress"
                ]
            ]);

            Log::channel('erenovate_debug')->info($response->getBody()->getContents());

        } catch (RequestException $e) {
//            dd($e->getRequest());
            Log::channel('erenovate_debug')->debug($e->getRequest());

        }

    }
}
