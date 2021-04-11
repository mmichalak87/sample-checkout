<?php

declare(strict_types=1);

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string summary
 * @property string invoice
 * @property float total
 */
final class CheckoutCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'summary' => 'required',
            'invoice' => 'required',
            'total' => 'required',
        ];
    }
}
