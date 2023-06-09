<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class SkipDefinedInMethodCallBigArray
{
    private function loadAlbums(ObjectManager $manager, array $images): array
    {
        return [$this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Vikings',
                'image' => 'vikings.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Night Falls', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Lost High', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Energy', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Let\'s Hurt Tonight', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Other Side', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Come Around', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'I Remember You', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Worthless Words', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Cheating Death', 'interpreter' => 'Civil Literature'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Civilwar',
                'image' => 'civilwar.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Clearview', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'The Game', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'My Children', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Homesick', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'We\'re right', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Apologize', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Home again', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'My Story', 'interpreter' => 'Civil Literature'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'collapse',
                'image' => 'collapse.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Shadows', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'My Champions', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'The Other Side', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'War', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Back in Time', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Other Side', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Hunt for Glory', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Devotion', 'interpreter' => 'Civil Literature'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => '#no more',
                'image' => 'no-more.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Turning Back', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Don\'t be Denied', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Carry On', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Peace', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Europe is Lost', 'interpreter' => 'Civil Literature'],
                    ['type' => 'track', 'title' => 'Break Through', 'interpreter' => 'Civil Literature'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Wildfire',
                'image' => 'forest.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'I won\'t go Back', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Count the Years', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Day after Day', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'A beautiful Girl', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Walk On', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Celebrate Life', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Promised Land', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Alive in us', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Dance you Life', 'interpreter' => 'Coyoos'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Cross the River',
                'image' => 'river.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Shelter from the Storm', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Sunrise', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Landscape', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Hotel in Chicago', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'The Horse in the small city', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Campfire in Murainen', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'A huge Drink', 'interpreter' => 'Coyoos'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Gold Digger',
                'image' => 'gold.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Gold Digger', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'First Autumn', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Under Valleys', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Concquer the Giants', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Wild World', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Go to San Francisco', 'interpreter' => 'Coyoos'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'The Wolves',
                'image' => 'wolves.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Tears come down', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'The Wolves', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Come Home', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Don\'t Worry Baby', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Mercy on the Road', 'interpreter' => 'Coyoos'],
                    ['type' => 'track', 'title' => 'Battle in the Nature', 'interpreter' => 'Coyoos'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Way',
                'image' => 'way.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Way Home', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Walk', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Come Together', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Living the Dream', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Mine', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Never to late', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Freedom', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Live - Follow', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Goal', 'interpreter' => 'Marshall Plan'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'let the light be',
                'image' => 'letthelightbe.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Cry', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Light', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Back to the Roots', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Needless', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Brighter', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Light the Sun', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Let the light be', 'interpreter' => 'Marshall Plan'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Variety',
                'image' => 'variety.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Think Different', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Maybe', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Endless chance', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Again again', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Right Now	', 'interpreter' => 'Marshall Plan'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Path',
                'image' => 'path.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Down the Way', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Step One', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Everything', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Ticket', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Learn again', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'No more valleys', 'interpreter' => 'Marshall Plan'],
                    ['type' => 'track', 'title' => 'Wonder', 'interpreter' => 'Marshall Plan'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Joy',
                'image' => 'vw.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Joy', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Find Me', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Take it Back', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Jump to heaven', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'More than this', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Turn Over', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Love is in the streets', 'interpreter' => 'The Bagpipes'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Busk',
                'image' => 'city.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Around', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Every Giant will Fall', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Royality', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Conquer more', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Live alive', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Battle Cry', 'interpreter' => 'The Bagpipes'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Bonfire',
                'image' => 'bonfire.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Celebration', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Catch a Glimpse', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Love your Neighbour', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Invest in me', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Movement', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Bagpipe Revival', 'interpreter' => 'The Bagpipes'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Scottlang Call\'s',
                'image' => 'isle.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Scottland Call\'s', 'interpreter' => 'The Bagpipes'],
                    ['type' => 'track', 'title' => 'Scottland Call\'s live', 'interpreter' => 'The Bagpipes'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Rebel',
                'image' => 'rebel.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Get It', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Fly', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Stick up', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => '2nd chance', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Neighbourhood', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Warning', 'interpreter' => 'TJ Fury'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'random',
                'image' => 'random.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Dope', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Punchline', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Don\'t give up', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Slippin', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Nervous', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'No more Violence', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Good Life', 'interpreter' => 'TJ Fury'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'down_town',
                'image' => 'down-town.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Rolling', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Child of the Ghetto', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Complete', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'This is my life', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'My mom', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Barbershop', 'interpreter' => 'TJ Fury'],
                ],
            ],
        ), $this->createAlbum(
            $manager,
            $images,
            [
                'title' => 'Railling',
                'image' => 'train.jpg',
                'tracklist' => [
                    ['type' => 'track', 'title' => 'Railling', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Friday Night', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Gucci', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Fresh Today', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'No Nobody', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'There it is', 'interpreter' => 'TJ Fury'],
                    ['type' => 'track', 'title' => 'Fight for your Aim', 'interpreter' => 'TJ Fury'],
                ],
            ],
        )];
    }
}