<?php

namespace Rector\Tests\Issues\Issue7099\Fixture;

use Rector\Tests\Issues\Issue7099\Source\Document;

class Controller
{
    public function getDocumentAction(int $id): ?Document
    {
        $document = $id === 1 ? new Document() : null;

        if (empty($document)) {
            return null;
        }

        return $document;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\Issue7099\Fixture;

use Rector\Tests\Issues\Issue7099\Source\Document;

class Controller
{
    public function getDocumentAction(int $id): ?Document
    {
        $document = $id === 1 ? new Document() : null;

        if (!$document instanceof \Rector\Tests\Issues\Issue7099\Source\Document) {
            return null;
        }

        return $document;
    }
}

?>
