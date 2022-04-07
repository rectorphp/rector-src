<?php

namespace Rector\Core\Tests\Issues\Issue7099\Fixture;

class Document {

}

class View {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    /** @return mixed */
    public function getData() {
        return $this->data;
    }
}

class DocumentRepository
{
    public function findById(int $id): ?Document {
        return $id === 1 ? new Document() : null;
    }
}

class SomeController
{
    private DocumentRepository $documentRepository;

    public function __construct(
        DocumentRepository $documentRepository,
    ) {
        $this->documentRepository = $documentRepository;
    }

    public function getDocumentAction(int $id): View
    {
        $document = $this->documentRepository->findById($id);

        if (empty($document)) {
            return $this->view([]);
        }

        return $this->view($document);
    }

    protected function view($data = null): View
    {
        return new View($data);
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue7099\Fixture;

class Document {

}

class View {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    /** @return mixed */
    public function getData() {
        return $this->data;
    }
}

class DocumentRepository
{
    public function findById(int $id): ?Document {
        return $id === 1 ? new Document() : null;
    }
}

class SomeController
{
    private DocumentRepository $documentRepository;

    public function __construct(
        DocumentRepository $documentRepository,
    ) {
        $this->documentRepository = $documentRepository;
    }

    public function getDocumentAction(int $id): View
    {
        $document = $this->documentRepository->findById($id);

        if (!$document instanceof \Rector\Core\Tests\Issues\Issue7099\Fixture\Document) {
            return $this->view([]);
        }

        return $this->view($document);
    }

    protected function view($data = null): View
    {
        return new View($data);
    }
}

?>
