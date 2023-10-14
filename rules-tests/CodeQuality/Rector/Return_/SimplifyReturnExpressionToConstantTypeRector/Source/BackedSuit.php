<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Source;


enum BackedSuit: string
{
    case Hearts = 'H';
    case Diamonds = 'D';
    case Clubs = 'C';
    case Spades = 'S';
}
