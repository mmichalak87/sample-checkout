<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Providers\MessageProvider;
use App\Providers\OrderProvider;
use Illuminate\Http\Request;
use TendoPay\SDK\Exception\TendoPayConnectionException;
use TendoPay\SDK\Models\VerifyTransactionRequest;
use TendoPay\SDK\V2\ConstantsV2;
use TendoPay\SDK\V2\TendoPayClient;

final class CheckoutController extends Controller
{
    private OrderProvider $orderProvider;
    private TendoPayClient $client;
    private MessageProvider $messages;

    public function __construct(
        OrderProvider $orderProvider,
        TendoPayClient $client,
        MessageProvider $messageProvider
    )
    {
        $this->orderProvider = $orderProvider;
        $this->client = $client;
        $this->messages = $messageProvider;
    }

    public function index()
    {
        return view('index');
    }

    public function confirm(Request $request)
    {
        $transactionDetails = null;

        try {
            if (TendoPayClient::isCallBackRequest($_REQUEST)) {
                $transaction = $this->client->verifyTransaction(new VerifyTransactionRequest($_REQUEST));

                if (!$transaction->isVerified()) {
                    throw new \UnexpectedValueException('Invalid signature for the verification');
                }

                switch ($transaction->getStatus()) {
                    case ConstantsV2::STATUS_SUCCESS:
                        $this->messages->addSuccess('Payment successful');
                        break;
                    case ConstantsV2::STATUS_FAILURE:
                        $this->messages->addError($transaction->getMessage());
                        $transactionDetails = $this->getTransactionData($request);
                        break;
                }
            }
        } catch (TendoPayConnectionException $e) {
            $this->messages->addError(sprintf('Connection Error: %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->messages->addError(sprintf('Runtime Error: %s', $e->getMessage()));
        }
        return view(
            'index',
            [
                'messages' => $this->messages,
                'transaction' => $transactionDetails
            ]
        );
    }

    private function getTransactionData(Request $request): array
    {
        if (null !== $request->input('tp_transaction_id', null)) {
            $order = $this->orderProvider->get((string)$request->input('tp_transaction_id'));
            if (null !== $order) {
                return [
                    'invoice' => $order->getMerchantOrderId(),
                    'total' => $order->getAmount()
                ];
            }
        }

        return [];
    }
}
