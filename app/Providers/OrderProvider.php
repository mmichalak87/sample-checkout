<?php

declare(strict_types=1);

namespace App\Providers;

use TendoPay\SDK\Models\Transaction;
use TendoPay\SDK\V2\TendoPayClient;

final class OrderProvider
{
    public function get(string $transactionNumber): ?Transaction
    {
        $client = new TendoPayClient();

        try {
            return $client->getTransactionDetail($transactionNumber);
        } catch (\Exception $e) {
            return null;
        }
    }

}
