<?php

declare(strict_types=1);

namespace Rector\Bench\Source;

use InvalidArgumentException;
use RuntimeException;
use Throwable;

final class NestedTryCatchSource
{
    public function run(array $items): int
    {
        $count = 0;

        foreach ($items as $item) {
            try {
                $count += $this->process($item);
            } catch (InvalidArgumentException $invalidArgumentException) {
                try {
                    $count += $this->retry($item, $invalidArgumentException->getMessage());
                } catch (RuntimeException $runtimeException) {
                    try {
                        $count += $this->fallback($item, $runtimeException->getCode());
                    } catch (Throwable $throwable) {
                        try {
                            $this->log($throwable->getMessage() . ' ' . $item);
                        } catch (Throwable) {
                            $count -= 1;
                        }
                    }
                }
            }
        }

        return $count;
    }

    public function sequential(string $value): string
    {
        try {
            $value = $this->normalize($value);
        } catch (InvalidArgumentException $invalidArgumentException) {
            $value = $invalidArgumentException->getMessage();
        }

        try {
            $value = strtoupper($this->normalize($value));
        } catch (RuntimeException $runtimeException) {
            try {
                $value = $this->normalize($runtimeException->getMessage());
            } catch (Throwable) {
                $value = '';
            }
        } finally {
            $value .= '!';
        }

        return $value;
    }

    private function process(mixed $item): int
    {
        return is_int($item) ? $item : throw new InvalidArgumentException('not int');
    }

    private function retry(mixed $item, string $reason): int
    {
        return is_numeric($item) ? (int) $item : throw new RuntimeException($reason);
    }

    private function fallback(mixed $item, int $code): int
    {
        return $code + (is_string($item) ? strlen($item) : 0);
    }

    private function log(string $message): void
    {
        error_log($message);
    }

    private function normalize(string $value): string
    {
        return trim($value) !== '' ? trim($value) : throw new InvalidArgumentException('empty');
    }
}
