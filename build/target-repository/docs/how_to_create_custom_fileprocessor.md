# Create your own custom FileProcessor

This section is all about creating your custom specific FileProcessor.
If you don´t know the concept of FileProcessors in the context of Rector, have a look at [Beyond PHP - Entering the realm of FileProcessors](beyond_php_file_processors.md)

Most of the examples starting with a rather contrived example, let´s do it the same.

Imagine you would like to replace the sentence "Make america great again" to "Make the whole world a better place to be" in every file named dumb_trump.txt.

In order to do so, we create the DumpTrumpFileProcessor like that:

```php
<?php
namespace MyVendor\MyPackage\FileProcessor;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;

final class DumpTrumpFileProcessor implements FileProcessorInterface
{
    /**
    * @var string
    */
    private const OLD_STATEMENT = 'Make america great again';

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();
        return 'dumb_trump.txt' === $smartFileInfo->getBasename();
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    private function processFile(File $file): void
    {
        $oldContent = $file->getFileContent();

        if(false === strpos($oldContent, self::OLD_STATEMENT)) {
            return;
        }

        $newFileContent = str_replace(self::OLD_STATEMENT, 'Make the whole world a better place to be', $oldContent);
        $file->changeFileContent($newFileContent);
    }

    public function getSupportedFileExtensions(): array
    {
        return ['txt'];
    }
}

```

Now register your FileProcessor in your configuration (actually in the container):

```php
<?php
// rector.php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MyVendor\MyPackage\FileProcessor\DumpTrumpFileProcessor;

return static function (ContainerConfigurator $containerConfigurator): void {
    // [...]
    $services = $containerConfigurator->services();
    $services->set(DumpTrumpFileProcessor::class);
};
```

Run rector again and see what happens. Yes, we made the world better.

The astute reader has noticed, that the DumpTrumpFileProcessor is not really reusable and easily extendable.
So it would be much better so separate the processing from the actual rule(s).
This is also the best practice in all Rector internal FileProcessors. So, let´s just do that.

Create a new dedicated Interface for our rules used by the DumpTrumpFileProcessor. Just call it DumpTrumpRectorInterface.

```php
<?php

namespace MyVendor\MyPackage\FileProcessor\Rector;

use Rector\Core\Contract\Rector\RectorInterface;

interface DumpTrumpRectorInterface extends RectorInterface
{
    public function transform(File $file): void;
}

```

Now, separate the modification from the processing:

```php
<?php

namespace MyVendor\MyPackage\FileProcessor\Rector;
use Rector\Core\ValueObject\Application\File;

final class DumpTrumpMakeTheWorldGreatAgainRector implements DumpTrumpRectorInterface
{
    /**
    * @var string
    */
    private const OLD_STATEMENT = 'Make america great again';

    public function transform(File $file): void
    {
        $oldContent = $file->getFileContent();

        if(false === strpos($oldContent, self::OLD_STATEMENT)) {
            return;
        }

        $newFileContent = str_replace(self::OLD_STATEMENT, 'Make the whole world a better place to be', $oldContent);
        $file->changeFileContent($newFileContent);
    }
}

```

And change our DumpTrumpFileProcessor so it is using one or multiple classes implementing the DumpTrumpRectorInterface:

```php
<?php

namespace MyVendor\MyPackage\FileProcessor;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use MyVendor\MyPackage\FileProcessor\Rector\DumpTrumpRectorInterface;

final class DumpTrumpFileProcessor implements FileProcessorInterface
{
    /**
    * @var  DumpTrumpRectorInterface[]
    */
    private $dumpTrumpRectors;

    /**
    * @param DumpTrumpRectorInterface[] $dumpTrumpRectors
    */
    public function __construct(array $dumpTrumpRectors)
    {
        $this->dumpTrumpRectors = $dumpTrumpRectors;
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();
        return 'dumb_trump.txt' === $smartFileInfo->getBasename();
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    private function processFile(File $file): void
    {
        foreach ($this->dumpTrumpRectors as $dumpTrumpRector) {
            $dumpTrumpRector->transform($file);
        }
    }

    public function getSupportedFileExtensions(): array
    {
        return ['txt'];
    }
}

```

Notice the annotation DumpTrumpRectorInterface[]. This is important to inject all active classes implementing the DumpTrumpRectorInterface into the DumpTrumpFileProcessor.
Yes, we said active. So last but not least we must register our new rule in the container, so it is applied:

```php
<?php
// rector.php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MyVendor\MyPackage\FileProcessor\DumpTrumpFileProcessor;
use MyVendor\MyPackage\FileProcessor\Rector\DumpTrumpMakeTheWorldGreatAgainRector;

return static function (ContainerConfigurator $containerConfigurator): void {
    // [...]
    $services = $containerConfigurator->services();
    $services->set(DumpTrumpFileProcessor::class);
    $services->set(DumpTrumpMakeTheWorldGreatAgainRector::class);
};
```

Run rector again and yes, we made the world a better place again.

Puh. This was a long ride. But we are done and have our new shiny DumpTrumpFileProcessor in place.
Now, it´s up to you, to create something useful. But always keep in mind: Make the world great again.
