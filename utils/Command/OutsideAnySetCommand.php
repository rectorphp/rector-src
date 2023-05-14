<?php

declare(strict_types=1);

namespace Rector\Utils\Command;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Set\ValueObject\SetList;
use Rector\Utils\Finder\RectorClassFinder;
use Rector\Utils\Finder\SetRectorClassesResolver;
use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class OutsideAnySetCommand extends Command
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('outside-any-set');
        $this->setDescription('[DEV] Show rules outside any set');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $setDefinedRectorClasses = $this->resolveSetDefinedRectorClasses();
        $existingRectorClasses = RectorClassFinder::find([__DIR__ . '/../../rules']);

        $rectorClassesOutsideAnySet = array_diff($existingRectorClasses, $setDefinedRectorClasses);

        sort($rectorClassesOutsideAnySet);

        $this->symfonyStyle->listing($rectorClassesOutsideAnySet);

        return self::SUCCESS;
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    private function resolveSetDefinedRectorClasses(): array
    {
        $setListReflectionClass = new ReflectionClass(SetList::class);

        $setDefinedRectorClasses = [];

        foreach ($setListReflectionClass->getConstants() as $constantValue) {
            $currentSetDefinedRectorClasses = SetRectorClassesResolver::resolve($constantValue);
            $setDefinedRectorClasses = array_merge($setDefinedRectorClasses, $currentSetDefinedRectorClasses);
        }

        return $setDefinedRectorClasses;
    }
}
