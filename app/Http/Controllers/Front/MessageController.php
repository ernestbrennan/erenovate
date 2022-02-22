<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;
use App\Travel\Messages\Exceptions\CreateMessageErrorException;
use App\Travel\Messages\Exceptions\UpdateMessageErrorException;
use App\Travel\Messages\Requests\{GetMessagesRequest, ReadMessageRequest, SendMessageRequest};
use App\Travel\Messages\Services\MessageService;

class MessageController extends Controller
{
    public $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function getList(GetMessagesRequest $request)
    {
        return response()->json($this->service->getMessagesByChat($request), 200);
    }

    public function sendMessage(SendMessageRequest $request)
    {
        try {
            return response()->json($this->service->sendMessage($request), 200);

        } catch (NotFoundGuaranteeProjectErrorException | CreateMessageErrorException $e) {
            return response()->json(422);
        }
    }

    public function readMessage(ReadMessageRequest $request)
    {

        try {
            return response()->json($this->service->readMessage($request), 200);

        } catch (UpdateMessageErrorException $e) {
            return response()->json(422);
        }

    }
}