<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

class SkipUsedMethodInNeverTypeScope
{
    protected bool $tokenizationEnabled = true;

    protected $queuedRecipients = [];

    public function send($isQueueFlush = false)
    {
        if ($this->tokenizationEnabled && !empty($this->queuedRecipients) && !$isQueueFlush) {
            return $this->queue();
        }

        if (!$isQueueFlush) {
            $this->setFromForSingleMessage();
        }

        return true;
    }

    public function queue()
    {
        if ($this->tokenizationEnabled) {
            return true;
        }

        return $this->send();
    }

    private function setFromForSingleMessage(): void
    {
        echo 'used';
    }
}
