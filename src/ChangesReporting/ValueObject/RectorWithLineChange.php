<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObject;

use Rector\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Symplify\EasyParallel\Contract\SerializableInterface;
use Webmozart\Assert\Assert;

final readonly class RectorWithLineChange implements SerializableInterface
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
     * @var class-string<RectorInterface|PostRectorInterface>
     */
    private string $rectorClass;

    /**
     * @param class-string<RectorInterface|PostRectorInterface>|RectorInterface|PostRectorInterface $rectorClass
     */
    public function __construct(
        string|RectorInterface|PostRectorInterface $rectorClass,
        private int $line
    ) {
        if ($rectorClass instanceof RectorInterface || $rectorClass instanceof PostRectorInterface) {
            $rectorClass = $rectorClass::class;
        }

        $this->rectorClass = $rectorClass;
    }

    /**
     * @return class-string<RectorInterface|PostRectorInterface>
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
        /** @var class-string<RectorInterface> $rectorClass */
        $rectorClass = $json[self::KEY_RECTOR_CLASS];
        Assert::string($rectorClass);

        $line = $json[self::KEY_LINE];
        Assert::integer($line);

        return new self($rectorClass, $line);
    }

    /**
     * @return array{rector_class: class-string<RectorInterface|PostRectorInterface>, line: int}
     */
    public function jsonSerialize(): array
    {
        return [
            self::KEY_RECTOR_CLASS => $this->rectorClass,
            self::KEY_LINE => $this->line,
        ];
    }
}
