<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Requests\CheckoutCreateRequest;
use Illuminate\Http\JsonResponse;
use TendoPay\SDK\Exception\TendoPayConnectionException;
use TendoPay\SDK\V2\TendoPayClient;

final class ApiCheckoutController extends Controller
{
    private TendoPayClient $client;

    public function __construct(
        TendoPayClient $client
    ) {
        $this->client = $client;
    }

    public function index(): JsonResponse
    {
        return response()->json([]);
    }

    public function create(CheckoutCreateRequest $request): JsonResponse
    {
        try {
            $payment = new Payment(
                $request->invoice,
                $request->summary,
                (float)$request->total
            );

            $this->client->setPayment($payment->get());

            $redirectURL = $this->client->getAuthorizeLink();
            return response()->json(['redirectURL' => $redirectURL]);
        } catch (TendoPayConnectionException $e) {
            return response()
                ->json(['message' => sprintf('Connection Error:%s', $e->getMessage())], 400);
        } catch (\Exception $e) {
            return response()
                ->json(['message' => sprintf('Runtime Error:%s', $e->getMessage())], 400);
        }
    }
}
