<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipEarlyReturnInCalledMethod
{
    private array $unknownLeadIds = [];

    public function __construct(
        private readonly array $campaignMembers,
    ) {
        $this->fetchLeads();
    }

    private function fetchLeads(): void
    {
        if ($this->campaignMembers === []) {
            return;
        }

        $this->unknownLeadIds = $this->campaignMembers;
    }
}
