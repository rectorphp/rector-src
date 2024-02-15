<?php

namespace Rector\Tests\Issues\EmptyLineNestedAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SegmentRepository")
 * @ORM\Table(
 *     name="segments",
 *
 *     indexes={
 *     @ORM\Index(name="sequence", columns={"sequence"}),
 *     @ORM\Index(name="status_channel_started_at", columns={"status", "channel", "started_at"}),
 *     @ORM\Index(name="representation_started_at", columns={"representation", "started_at"}),
 *     @ORM\Index(name="channel_started_at_status_representation_sequence", columns={"channel", "started_at", "status", "representation", "sequence"})
 * },
 *     uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_channel_representation_started_at", columns={"channel", "representation", "started_at"})
 * }
 *     )
 */
class Segment
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\EmptyLineNestedAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'segments')]
#[ORM\Index(name: 'sequence', columns: ['sequence'])]
#[ORM\Index(name: 'status_channel_started_at', columns: ['status', 'channel', 'started_at'])]
#[ORM\Index(name: 'representation_started_at', columns: ['representation', 'started_at'])]
#[ORM\Index(name: 'channel_started_at_status_representation_sequence', columns: ['channel', 'started_at', 'status', 'representation', 'sequence'])]
#[ORM\UniqueConstraint(name: 'unique_channel_representation_started_at', columns: ['channel', 'representation', 'started_at'])]
#[ORM\Entity(repositoryClass: \App\Repository\SegmentRepository::class)]
class Segment
{
}

?>
