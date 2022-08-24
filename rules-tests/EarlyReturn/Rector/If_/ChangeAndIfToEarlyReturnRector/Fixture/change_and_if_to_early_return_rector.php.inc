<?php

function dummy_wrap($maxWidth, $text)
{
    $wmax = ($maxWidth - 2 * MARGIN);
    $cw   = &get_font()['cw'];
    $s    = str_replace("\r", '', (string) $text);

    if (get_font_set()) {
        $nb = mb_strlen($s, 'utf-8');
        while ($nb > 0 && mb_substr($s, $nb - 1, 1, 'utf-8') == "\n") {
            $nb--;
        }
    } else {
        $nb = strlen($s);

        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
    }

    $sep = -1;
    $i   = 0;
    $j   = 0;
    $l   = 0;
    $nl  = 1;

    while ($i < $nb) {
        $c = $s[$i];

        if ($c == "\n") {
            $i++;
            $sep = -1;
            $j   = $i;
            $l   = 0;

            $nl++;

            continue;
        }

        if ($c == ' ') {
            $sep = $i;
        }

        $l += $cw[$c] * FONT_SIZE / 1000;

        if ($l > $wmax) {
            if ($sep == -1) {
                if ($i == $j) {
                    $i++;
                }
            } else {
                $i = $sep + 1;
            }
            $sep = -1;
            $j   = $i;
            $l   = 0;

            $nl++;
        } else {
            $i++;
        }
    }

    return $nl;
}

?>
