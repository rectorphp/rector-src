<?php

enum UtilizationType: string
{
    case Apartment = 'apartment';
    case Commerce = 'commerce';
    case Flat = 'flat';
    case Garage = 'garage';
    case Garden = 'garden';
    case Hospitality = 'hospitality';
    case House = 'house';
    case Investment = 'investment';
    case Office = 'office';
    case Retirement = 'retirement';
    case Warehouse = 'warehouse';

    private const BUSINESS_TYPES = [
        self::Commerce,
        self::Hospitality,
        self::Office,
        self::Warehouse,
    ];

    public function isBusiness(): bool
    {
        return in_array($this, self::BUSINESS_TYPES, true);
    }
}

?>
-----
