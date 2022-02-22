<?php

namespace App\Http\Controllers\Front;

use App\Events\ContractSignedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment as R;
use App\Services\PayPalService;
use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Milestones\Milestone;
use App\Travel\Payments\PaypalLog;
use App\Travel\SystemNotifications\Contract\SystemNotificationContractCompleted;
use App\Travel\SystemNotifications\Contract\SystemNotificationContractSigned;
use Illuminate\Http\Response;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Sample\PayPalClient;

class PaymentController extends Controller
{
    public function contractFee(R\PayContractFeeRequest $request)
    {
        $contract_draft_id = $request->input('contract_draft_id');
        $order_id = $request->input('order_id');

        $client = PayPalService::client();
        $response = $client->execute(new OrdersGetRequest($order_id));

        $contract_draft = ContractDraft::with([
            'contract',
            'milestones'
        ])
            ->find($contract_draft_id);

        $contract = $contract_draft->contract;

        $paypal_log = [
            'status_code' => $response->statusCode,
            'status' => $response->result->status,
            'order_id' => $response->result->id,
            'intent' => $response->result->intent,
            'gross_amount' => $response->result->purchase_units[0]->amount->value,
            'currency_code' => $response->result->purchase_units[0]->amount->currency_code
        ];

        if ($paypal_log['status'] === PaypalLog::STATUS_COMPLETED) {

            $contract_draft->milestones[0]->update(['status' => Milestone::STATUS_IN_PROGRESS]);
            $contract->update(['status' => Contract::STATUS_SIGNED]);

            event(new ContractSignedEvent($contract->guarantee_project));
            SystemNotificationContractSigned::doNotification($contract);
        }

        PaypalLog::query()->create([
            'contract_id' => $contract['id'],
            'order_id' => $order_id,
            'type' => PaypalLog::TYPE_SIGNED,
            'log' => $paypal_log
        ]);

        return response()->json([
            'response' => $paypal_log,
            'status_code' => $response->statusCode,
        ], Response::HTTP_OK);
    }

    public function summaryFee(R\PaySummaryFeeRequest $request)
    {
        $contract_id = $request->input('contract_id');
        $order_id = $request->input('order_id');

        $client = PayPalService::client();
        $response = $client->execute(new OrdersGetRequest($order_id));

        $contract = Contract::find($contract_id);

        $paypal_log = [
            'status_code' => $response->statusCode,
            'status' => $response->result->status,
            'order_id' => $response->result->id,
            'intent' => $response->result->intent,
            'gross_amount' => $response->result->purchase_units[0]->amount->value,
            'currency_code' => $response->result->purchase_units[0]->amount->currency_code
        ];

        if ($paypal_log['status'] === PaypalLog::STATUS_COMPLETED) {

            $contract->update(['status' => Contract::STATUS_COMPLETED]);

//            SystemNotificationContractCompleted::doNotification($contract);
        }

        PaypalLog::query()->create([
            'contract_id' => $contract['id'],
            'order_id' => $order_id,
            'type' => PaypalLog::TYPE_SIGNED,
            'log' => $paypal_log
        ]);

        return response()->json([
            'response' => $paypal_log,
            'status_code' => $response->statusCode,
        ], Response::HTTP_OK);
    }
}
