<?php

declare(strict_types=1);

namespace Rector\Bench\Source;

/**
 * Dead statements after a label no goto jumps to; PHPStan walks none of them.
 */
final class GotoLabelSource
{
    public function run1(int $value): int
    {
        if ($value > 1) {
            goto end;
            dead:
            $items = [$value, 1];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 1;
        return $value;
    }

    public function run2(int $value): int
    {
        if ($value > 2) {
            goto end;
            dead:
            $items = [$value, 2];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 2;
        return $value;
    }

    public function run3(int $value): int
    {
        if ($value > 3) {
            goto end;
            dead:
            $items = [$value, 3];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 3;
        return $value;
    }

    public function run4(int $value): int
    {
        if ($value > 4) {
            goto end;
            dead:
            $items = [$value, 4];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 4;
        return $value;
    }

    public function run5(int $value): int
    {
        if ($value > 5) {
            goto end;
            dead:
            $items = [$value, 5];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 5;
        return $value;
    }

    public function run6(int $value): int
    {
        if ($value > 6) {
            goto end;
            dead:
            $items = [$value, 6];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 6;
        return $value;
    }

    public function run7(int $value): int
    {
        if ($value > 7) {
            goto end;
            dead:
            $items = [$value, 7];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 7;
        return $value;
    }

    public function run8(int $value): int
    {
        if ($value > 8) {
            goto end;
            dead:
            $items = [$value, 8];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 8;
        return $value;
    }

    public function run9(int $value): int
    {
        if ($value > 9) {
            goto end;
            dead:
            $items = [$value, 9];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 9;
        return $value;
    }

    public function run10(int $value): int
    {
        if ($value > 10) {
            goto end;
            dead:
            $items = [$value, 10];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 10;
        return $value;
    }

    public function run11(int $value): int
    {
        if ($value > 11) {
            goto end;
            dead:
            $items = [$value, 11];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 11;
        return $value;
    }

    public function run12(int $value): int
    {
        if ($value > 12) {
            goto end;
            dead:
            $items = [$value, 12];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 12;
        return $value;
    }

    public function run13(int $value): int
    {
        if ($value > 13) {
            goto end;
            dead:
            $items = [$value, 13];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 13;
        return $value;
    }

    public function run14(int $value): int
    {
        if ($value > 14) {
            goto end;
            dead:
            $items = [$value, 14];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 14;
        return $value;
    }

    public function run15(int $value): int
    {
        if ($value > 15) {
            goto end;
            dead:
            $items = [$value, 15];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 15;
        return $value;
    }

    public function run16(int $value): int
    {
        if ($value > 16) {
            goto end;
            dead:
            $items = [$value, 16];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 16;
        return $value;
    }

    public function run17(int $value): int
    {
        if ($value > 17) {
            goto end;
            dead:
            $items = [$value, 17];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 17;
        return $value;
    }

    public function run18(int $value): int
    {
        if ($value > 18) {
            goto end;
            dead:
            $items = [$value, 18];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 18;
        return $value;
    }

    public function run19(int $value): int
    {
        if ($value > 19) {
            goto end;
            dead:
            $items = [$value, 19];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 19;
        return $value;
    }

    public function run20(int $value): int
    {
        if ($value > 20) {
            goto end;
            dead:
            $items = [$value, 20];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 20;
        return $value;
    }

    public function run21(int $value): int
    {
        if ($value > 21) {
            goto end;
            dead:
            $items = [$value, 21];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 21;
        return $value;
    }

    public function run22(int $value): int
    {
        if ($value > 22) {
            goto end;
            dead:
            $items = [$value, 22];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 22;
        return $value;
    }

    public function run23(int $value): int
    {
        if ($value > 23) {
            goto end;
            dead:
            $items = [$value, 23];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 23;
        return $value;
    }

    public function run24(int $value): int
    {
        if ($value > 24) {
            goto end;
            dead:
            $items = [$value, 24];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 24;
        return $value;
    }

    public function run25(int $value): int
    {
        if ($value > 25) {
            goto end;
            dead:
            $items = [$value, 25];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 25;
        return $value;
    }

    public function run26(int $value): int
    {
        if ($value > 26) {
            goto end;
            dead:
            $items = [$value, 26];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 26;
        return $value;
    }

    public function run27(int $value): int
    {
        if ($value > 27) {
            goto end;
            dead:
            $items = [$value, 27];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 27;
        return $value;
    }

    public function run28(int $value): int
    {
        if ($value > 28) {
            goto end;
            dead:
            $items = [$value, 28];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 28;
        return $value;
    }

    public function run29(int $value): int
    {
        if ($value > 29) {
            goto end;
            dead:
            $items = [$value, 29];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 29;
        return $value;
    }

    public function run30(int $value): int
    {
        if ($value > 30) {
            goto end;
            dead:
            $items = [$value, 30];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 30;
        return $value;
    }

    public function run31(int $value): int
    {
        if ($value > 31) {
            goto end;
            dead:
            $items = [$value, 31];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 31;
        return $value;
    }

    public function run32(int $value): int
    {
        if ($value > 32) {
            goto end;
            dead:
            $items = [$value, 32];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 32;
        return $value;
    }

    public function run33(int $value): int
    {
        if ($value > 33) {
            goto end;
            dead:
            $items = [$value, 33];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 33;
        return $value;
    }

    public function run34(int $value): int
    {
        if ($value > 34) {
            goto end;
            dead:
            $items = [$value, 34];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 34;
        return $value;
    }

    public function run35(int $value): int
    {
        if ($value > 35) {
            goto end;
            dead:
            $items = [$value, 35];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 35;
        return $value;
    }

    public function run36(int $value): int
    {
        if ($value > 36) {
            goto end;
            dead:
            $items = [$value, 36];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 36;
        return $value;
    }

    public function run37(int $value): int
    {
        if ($value > 37) {
            goto end;
            dead:
            $items = [$value, 37];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 37;
        return $value;
    }

    public function run38(int $value): int
    {
        if ($value > 38) {
            goto end;
            dead:
            $items = [$value, 38];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 38;
        return $value;
    }

    public function run39(int $value): int
    {
        if ($value > 39) {
            goto end;
            dead:
            $items = [$value, 39];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 39;
        return $value;
    }

    public function run40(int $value): int
    {
        if ($value > 40) {
            goto end;
            dead:
            $items = [$value, 40];
            foreach ($items as $item) {
                $value += $item;
            }

            end:
        }

        return $value;
        unreachable:
        $value = $value * 40;
        return $value;
    }
}
