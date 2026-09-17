<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector\Fixture;

final class OverrideInBothBranches
{
    public function run(string $state, array $present, int $id): array
    {
        $settings = [];
        if ($state === 'enabled') {
            $settings = $this->getDefaults();
            foreach ($settings as $key => $value) {
                if (array_key_exists($key, $present)) {
                    unset($settings[$key]);
                }
            }

            $settings['enabled'] = 'yes';
        } else {
            $settings = ['enabled' => 'no', 'id' => $id];
        }

        return $settings;
    }

    private function getDefaults(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector\Fixture;

final class OverrideInBothBranches
{
    public function run(string $state, array $present, int $id): array
    {
        if ($state === 'enabled') {
            $settings = $this->getDefaults();
            foreach ($settings as $key => $value) {
                if (array_key_exists($key, $present)) {
                    unset($settings[$key]);
                }
            }

            $settings['enabled'] = 'yes';
        } else {
            $settings = ['enabled' => 'no', 'id' => $id];
        }

        return $settings;
    }

    private function getDefaults(): array
    {
        return [];
    }
}

?>
