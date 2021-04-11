<?php

declare(strict_types=1);

namespace App\Providers;

final class MessageProvider implements \JsonSerializable
{
    private array $messages = [];

    private const ERROR_TYPE = 'danger';
    private const SUCCESS_TYPE = 'success';

    public function addError(string $message): void
    {
        $this->add($message, self::ERROR_TYPE);
    }

    public function addSuccess(string $message): void
    {
        $this->add($message, self::SUCCESS_TYPE);
    }

    private function add(string $message, string $type): void
    {
        $this->messages[] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->messages;
    }
}
