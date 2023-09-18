<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObject;

use Rector\Core\Contract\Rector\CollectorRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Symplify\EasyParallel\Contract\SerializableInterface;
use Webmozart\Assert\Assert;

final class RectorWithLineChange implements SerializableInterface
{
    /**
     * @var string
     */
    private const KEY_RECTOR_CLASS = 'rector_class';

    /**
     * @var string
     */
    private const KEY_LINE = 'line';

    /**
     * @var class-string<RectorInterface|CollectorRectorInterface>
     */
    private readonly string $rectorClass;

    /**
     * @param class-string<RectorInterface|CollectorRectorInterface>|RectorInterface|CollectorRectorInterface $rectorClass
     */
    public function __construct(
        string|RectorInterface|CollectorRectorInterface $rectorClass,
        private readonly int $line
    ) {
        if ($rectorClass instanceof RectorInterface || $rectorClass instanceof CollectorRectorInterface) {
            $rectorClass = $rectorClass::class;
        }

        $this->rectorClass = $rectorClass;
    }

    /**
     * @return class-string<RectorInterface|CollectorRectorInterface>
     */
    public function getRectorClass(): string
    {
        return $this->rectorClass;
    }

    /**
     * @param array<string, mixed> $json
     */
    public static function decode(array $json): self
    {
        $rectorClass = $json[self::KEY_RECTOR_CLASS];
        Assert::string($rectorClass);

        $line = $json[self::KEY_LINE];
        Assert::integer($line);

        return new self($rectorClass, $line);
    }

    /**
     * @return array{rector_class: class-string<RectorInterface|CollectorRectorInterface>, line: int}
     */
    public function jsonSerialize(): array
    {
        return [
            self::KEY_RECTOR_CLASS => $this->rectorClass,
            self::KEY_LINE => $this->line,
        ];
    }
}
