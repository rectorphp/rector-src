<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SkipReprintDoc
{
    /**
     * Recursively traverse the Tiptap document tree to find user mentions
     *
     * @param  array|null  $node  The current node to check
     * @param  mixed[]  $mentions  Array to collect mentions
     */
    private function traverseNodes($node, array &$mentions): void
    {
        if (! $node || ! is_array($node)) {
            return;
        }

        // Check if current node is a user mention
        if (isset($node['type']) && $node['type'] === 'userMention' && isset($node['attrs']['user'])) {
            $mentions[] = $node['attrs']['user'];
        }

        // Check content array
        if (isset($node['content']) && is_array($node['content'])) {
            foreach ($node['content'] as $childNode) {
                $this->traverseNodes($childNode, $mentions);
            }
        }
    }
}
