<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Attachment;
use Doctrine\Common\Collections\Collection;

trait AttachmentEntity
{
    /** @var Collection<int, Attachment> */
    private Collection $attachments;

    public function getAttachments() : Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment) : static
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    public function removeAttachment(Attachment $attachment) : static
    {
        $this->attachments->removeElement($attachment);

        return $this;
    }

    public function setAttachments(Collection $attachments) : static
    {
        $this->attachments = $attachments;

        return $this;
    }
}
