<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class FDocentete
{
    public function __construct(private string $doPiece)
    {
    }

    public function getDoPiece(): ?string
    {
        return $this->doPiece;
    }

    public function setDoPiece(?string $doPiece): self
    {
        $this->doPiece = $doPiece;
        return $this;
    }
}

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class GetFDocentetesController
{
    public static function test()
    {
        $fDoclignes = [
            ['doPiece' => '1']
        ];
        $fDocentetes = [
            new FDocentete('1'),
            new FDocentete('2'),
        ];
        /** @var FDocentete $currentFDocentete */
        $currentFDocentete = null;
        foreach ($fDoclignes as $fDocligne) {
            if (is_null($currentFDocentete) || $currentFDocentete->getDoPiece() !== $fDocligne['doPiece']) {
                if (!is_null($currentFDocentete)) {
                    echo 'I echo here';
                }
                $currentFDocentete = current(array_filter($fDocentetes, static fn(FDocentete $fDocentete) => $fDocentete->getDoPiece() === $fDocligne['doPiece']));
            }
        }
    }
}

GetFDocentetesController::test();

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class FDocentete
{
    public function __construct(private string $doPiece)
    {
    }

    public function getDoPiece(): ?string
    {
        return $this->doPiece;
    }

    public function setDoPiece(?string $doPiece): self
    {
        $this->doPiece = $doPiece;
        return $this;
    }
}

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class GetFDocentetesController
{
    public static function test()
    {
        $fDoclignes = [
            ['doPiece' => '1']
        ];
        $fDocentetes = [
            new FDocentete('1'),
            new FDocentete('2'),
        ];
        /** @var FDocentete $currentFDocentete */
        $currentFDocentete = null;
        foreach ($fDoclignes as $fDocligne) {
            if (is_null($currentFDocentete) || $currentFDocentete->getDoPiece() !== $fDocligne['doPiece']) {
                if (!is_null($currentFDocentete)) {
                    echo 'I echo here';
                }
                $currentFDocentete = current(array_filter($fDocentetes, static fn(FDocentete $fDocentete) => $fDocentete->getDoPiece() === $fDocligne['doPiece']));
            }
        }
    }
}

GetFDocentetesController::test();

?>
