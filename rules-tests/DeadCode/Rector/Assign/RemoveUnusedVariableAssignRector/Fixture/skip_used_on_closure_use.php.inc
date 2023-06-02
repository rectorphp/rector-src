<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

class SkipUsedOnClosureUse {
    public function article(string $id): array
    {
        $johnSmith = null;
        $article = static function (string $id) use (&$johnSmith): array {
            return [
                'author' => $johnSmith,
            ];
        };

        $johnSmith = [
            'id' => 123,
            'name' => 'John Smith',
            'recentArticle' => $article('1'),
        ];

        return $article($id);
    }
}