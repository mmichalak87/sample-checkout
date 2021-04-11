<?php

declare(strict_types=1);

namespace App\Models;

final class Payment
{
    private string $invoice;
    private string $summary;
    private float $total;

    public function __construct(string $invoice, string $summary, float $total)
    {
        $this->invoice = $invoice;
        $this->summary = $summary;
        $this->total = $total;
    }

    public function get(): \TendoPay\SDK\Models\Payment
    {
        $payment = new \TendoPay\SDK\Models\Payment();
        $payment->setMerchantOrderId($this->invoice)
            ->setDescription($this->summary)
            ->setRequestAmount((float)$this->total)
            ->setCurrency(config('tendopay.currency'))
            ->setRedirectUrl(config('tendopay.redirect_url'));
        return $payment;
    }

}
