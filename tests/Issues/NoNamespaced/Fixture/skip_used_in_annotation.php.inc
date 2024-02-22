<?php

use App\Repository\DemoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemoRepository::class)
 */
final class SkipUsedInAnnotation
{
    /**
     * @ORM\Column(type=Types::STRING, length=100, nullable=false)
     */
    protected $status;
}
