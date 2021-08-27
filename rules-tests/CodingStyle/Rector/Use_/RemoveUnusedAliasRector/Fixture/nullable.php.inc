<?php

declare(strict_types=1);

namespace App\Reservation;

use App\Entity\Reservation as ReservationEntity;
use JetBrains\PhpStorm\Immutable;

namespace Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Fixture;

final class Result
{
    private function __construct(
        #[Immutable]
        public ?ReservationEntity $reservationEntity,
        #[Immutable]
        public ?string $errorMessage
    ) {
    }

    public static function createSuccessful(ReservationEntity $reservation): Result
    {
        return new self($reservation, null);
    }

    public static function createUnsuccessful(string $error): Result
    {
        return new self(null, $error);
    }
}
?>
-----
<?php

declare(strict_types=1);

namespace App\Reservation;

use App\Entity\Reservation;
use JetBrains\PhpStorm\Immutable;

namespace Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Fixture;

final class Result
{
    private function __construct(
        #[Immutable]
        public ?Reservation $reservationEntity,
        #[Immutable]
        public ?string $errorMessage
    ) {
    }

    public static function createSuccessful(Reservation $reservation): Result
    {
        return new self($reservation, null);
    }

    public static function createUnsuccessful(string $error): Result
    {
        return new self(null, $error);
    }
}
?>
