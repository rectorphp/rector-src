<html>
    Some HTML content
</html>

<?php

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

final class WithHTML
{
    /**
     * @JoinTable(name="lemma_type",
     *      inverseJoinColumns={@JoinColumn(name="lemma_id", referencedColumnName="lemma_id")}
     * )
     */
    private iterable $lemmas;
}

?>
-----
<html>
    Some HTML content
</html>

<?php

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

final class WithHTML
{
    #[JoinTable(name: 'lemma_type')]
    #[\Doctrine\ORM\Mapping\InverseJoinColumn(name: 'lemma_id', referencedColumnName: 'lemma_id')]
    private iterable $lemmas;
}

?>
