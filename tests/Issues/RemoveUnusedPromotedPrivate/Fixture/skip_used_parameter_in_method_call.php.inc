<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\RemoveUnusedPromotedPrivate\Fixture;

final class DemoSetting extends AbstractSetting
{
    private PageInterface $page;
    private SectionInterface $section;

	public function __construct(PageInterface $page, SectionInterface $section)
    {
        $this->page = $page;
        $this->section = $section;

        parent::__construct($page, $section);
    }

    public function getPage()
    {
        return $this->page;
    }
}

abstract class AbstractSetting
{
	public function __construct(PageInterface $page, SectionInterface $section)
    {
        // do something with $page and $section
        $page->runSomething();
        $section->runSomething();
    }
}
