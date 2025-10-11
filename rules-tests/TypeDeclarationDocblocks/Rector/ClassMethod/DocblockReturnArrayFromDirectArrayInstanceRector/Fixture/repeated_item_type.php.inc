<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class RepeatedItemType
{
    public function __construct(
        public readonly string $collectionName,
        public readonly ?string $partitionName = null,
        public readonly ?int $replicaNumber = null,
        public readonly ?int $timeout = null,
    ) {}

    public function toRequestPayload(): array
    {
        $params = [
            'partition_name' => $this->partitionName,
            'replica_number' => $this->replicaNumber,
            'timeout' => $this->timeout,
        ];

        $params = array_filter($params);

        return [
            'collection_name' => $this->collectionName,
            'params' => $params,
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class RepeatedItemType
{
    public function __construct(
        public readonly string $collectionName,
        public readonly ?string $partitionName = null,
        public readonly ?int $replicaNumber = null,
        public readonly ?int $timeout = null,
    ) {}

    /**
     * @return array<string, string|array<string, int|string>>
     */
    public function toRequestPayload(): array
    {
        $params = [
            'partition_name' => $this->partitionName,
            'replica_number' => $this->replicaNumber,
            'timeout' => $this->timeout,
        ];

        $params = array_filter($params);

        return [
            'collection_name' => $this->collectionName,
            'params' => $params,
        ];
    }
}

?>
