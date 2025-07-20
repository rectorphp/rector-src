<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

final class SkipUsedInCompact
{
    public function run(array $renames)
    {
        foreach ($renames as $morph => $rename) {
            foreach ($rename as $old_key => $new_key) {
                DB::statement(<<<'SQL'
                    WITH cte AS (
                        SELECT id,
                            CASE
                                WHEN new_values ?? :old_key THEN
                                    jsonb_set(
                                        new_values - :old_key,
                                        ARRAY[:new_key],
                                        new_values->:old_key,
                                        true
                                    )
                                ELSE new_values
                            END AS new_values,
                            CASE
                                WHEN old_values ?? :old_key THEN
                                    jsonb_set(
                                        old_values - :old_key,
                                        ARRAY[:new_key],
                                        old_values->:old_key,
                                        true
                                    )
                                ELSE old_values
                            END AS old_values
                        FROM audits
                        WHERE auditable_type = :morph
                            AND (new_values ?? :old_key OR old_values ?? :old_key)
                    )
                    UPDATE audits
                    SET new_values = cte.new_values,
                        old_values = cte.old_values
                    FROM cte
                    WHERE audits.id = cte.id
                    SQL, compact('morph', 'old_key', 'new_key'));
            }
        }
    }
}
