<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

class SkipWithHTML
{
    private function renderMenu(): void {
		?>
        <div>
            <?php if ($menuItem->is_main_item) : ?>
                <a href="#" class="<?= $aClass ?>">
                    <div class="flex items-center gap-2 pointer-events-none">
                        <?= $menuItem->icon ? $this->Icon->render($menuItem->icon) : '' ?>
                        <?= $title ?>
                    </div>
                </a>
            <?php else :
                if ($menuItem->is_external_url) {
                    echo $this->Html->link($title, $menuItem->url, [
                        'menu-icon' => $menuItem->icon ?? false,
                        'class' => $aClass,
                    ]);
                } elseif ($menuItem->parsed_url && $menuItem->is_url_valid) {
                    echo $this->Html->linkFromPath($title, $menuItem->parsed_url, [], [
                        'menu-icon' => $menuItem->icon ?? false,
                        'class' => $aClass,
                    ]);
                } else {
                    // Do something?
                }
            endif; ?>
		</div>
        <?php
    }
}