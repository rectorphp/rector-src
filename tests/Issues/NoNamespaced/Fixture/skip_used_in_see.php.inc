<?php

use src\Event\Infrastructure\API\GooglePlaces\Place;

final readonly class Location
{
    /**
     * @param list<string> $types
     */
    public function __construct(
        public array $types,
    ) {}

    /**
     * Returns true if the location is a city
     *
     * @see Place::isCity()
     */
    public function isCity() : bool
    {
        return in_array('locality', $this->types, true);
    }
}
