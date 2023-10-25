<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Naming\ExpectedNameResolver;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Naming\ExpectedNameResolver\InflectorSingularResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class InflectorSingularResolverTest extends AbstractLazyTestCase
{
    private InflectorSingularResolver $inflectorSingularResolver;

    protected function setUp(): void
    {
        $this->inflectorSingularResolver = $this->make(InflectorSingularResolver::class);
    }

    #[DataProvider('provideData')]
    public function testResolveForForeach(string $currentName, string $expectedSingularName): void
    {
        $singularValue = $this->inflectorSingularResolver->resolve($currentName);
        $this->assertSame($expectedSingularName, $singularValue);
    }

    public static function provideData(): Iterator
    {
        yield ['psr4NamespacesToPaths', 'psr4NamespacesToPath'];
        yield ['nestedNews', 'nestedNews'];
        yield ['news', 'news'];
        yield ['property', 'property'];
        yield ['argsOrOptions', 'argsOrOption'];
        yield ['class', 'class'];

        // news and plural
        yield ['staticCallsToNews', 'staticCallsToNews'];
        yield ['newsToMethodCalls', 'newsToMethodCall'];
        yield ['hasFilters', 'hasFilter'];
        yield ['parametersAcceptor', 'parametersAcceptor'];
        yield ['parametersAcceptorWithPhpDoc', 'parametersAcceptorWithPhpDoc'];
        yield ['dogsCleaners', 'dogsCleaner'];

        // doctrine-inflector tests
        yield ['abilities', 'ability'];
        yield ['abuses', 'abuse'];
        yield ['acceptancecriteria', 'acceptancecriterion'];
        yield ['addresses', 'address'];
        yield ['advice', 'advice'];
        yield ['agencies', 'agency'];
        yield ['aircraft', 'aircraft'];
        yield ['aliases', 'alias'];
        yield ['alumni', 'alumnus'];
        yield ['amoyese', 'amoyese'];
        yield ['analyses', 'analysis'];
        yield ['aquaria', 'aquarium'];
        yield ['arches', 'arch'];
        yield ['archives', 'archive'];
        yield ['art', 'art'];
        yield ['atlases', 'atlas'];
        yield ['audio', 'audio'];
        yield ['avalanches', 'avalanche'];
        yield ['axes', 'axe'];
        yield ['babies', 'baby'];
        yield ['bacilli', 'bacillus'];
        yield ['bacteria', 'bacterium'];
        yield ['baggage', 'baggage'];
        yield ['bases', 'basis'];
        yield ['bison', 'bison'];
        yield ['blouses', 'blouse'];
        yield ['borghese', 'borghese'];
        yield ['boxes', 'box'];
        yield ['bream', 'bream'];
        yield ['breeches', 'breeches'];
        yield ['britches', 'britches'];
        yield ['buffalo', 'buffalo'];
        yield ['bureaus', 'bureau'];
        yield ['buses', 'bus'];
        yield ['butter', 'butter'];
        yield ['caches', 'cache'];
        yield ['cacti', 'cactus'];
        yield ['cafes', 'cafe'];
        yield ['calves', 'calf'];
        yield ['cantus', 'cantus'];
        yield ['canvases', 'canvas'];
        yield ['carp', 'carp'];
        yield ['cases', 'case'];
        yield ['caves', 'cave'];
        yield ['categorias', 'categoria'];
        yield ['categories', 'category'];
        yield ['cattle', 'cattle'];
        yield ['chassis', 'chassis'];
        yield ['chateaux', 'chateau'];
        yield ['cherries', 'cherry'];
        yield ['children', 'child'];
        yield ['churches', 'church'];
        yield ['circuses', 'circus'];
        yield ['cities', 'city'];
        yield ['clippers', 'clippers'];
        yield ['clothes', 'clothes'];
        yield ['clothing', 'clothing'];
        yield ['coal', 'coal'];
        yield ['cod', 'cod'];
        yield ['coitus', 'coitus'];
        yield ['comments', 'comment'];
        yield ['compensation', 'compensation'];
        yield ['congoese', 'congoese'];
        yield ['contretemps', 'contretemps'];
        yield ['cookies', 'cookie'];
        yield ['copies', 'copy'];
        yield ['coreopsis', 'coreopsis'];
        yield ['corps', 'corps'];
        yield ['cotton', 'cotton'];
        yield ['cows', 'cow'];
        yield ['crises', 'crisis'];
        yield ['criteria', 'criterion'];
        yield ['currencies', 'currency'];
        yield ['curricula', 'curriculum'];
        yield ['curves', 'curve'];
        yield ['data', 'data'];
        yield ['databases', 'database'];
        yield ['days', 'day'];
        yield ['debris', 'debris'];
        yield ['deer', 'deer'];
        yield ['demos', 'demo'];
        yield ['diabetes', 'diabetes'];
        yield ['diagnoses', 'diagnosis'];
        yield ['dictionaries', 'dictionary'];
        yield ['dives', 'dive'];
        yield ['djinn', 'djinn'];
        yield ['dominoes', 'domino'];
        yield ['dwarves', 'dwarf'];
        yield ['echoes', 'echo'];
        yield ['edges', 'edge'];
        yield ['education', 'education'];
        yield ['eland', 'eland'];
        yield ['elves', 'elf'];
        yield ['elk', 'elk'];
        yield ['emoji', 'emoji'];
        yield ['emphases', 'emphasis'];
        yield ['energies', 'energy'];
        yield ['epochs', 'epoch'];
        yield ['equipment', 'equipment'];
        yield ['evidence', 'evidence'];
        yield ['experiences', 'experience'];
        yield ['families', 'family'];
        yield ['faroese', 'faroese'];
        yield ['faxes', 'fax'];
        yield ['feedback', 'feedback'];
        yield ['fish', 'fish'];
        yield ['fixes', 'fix'];
        yield ['flounder', 'flounder'];
        yield ['flour', 'flour'];
        yield ['flushes', 'flush'];
        yield ['flies', 'fly'];
        yield ['foci', 'focus'];
        yield ['foes', 'foe'];
        yield ['foobars', 'foobar'];
        yield ['foochowese', 'foochowese'];
        yield ['food', 'food'];
        yield ['food_menus', 'food_menu'];
        yield ['foodmenus', 'foodmenu'];
        yield ['feet', 'foot'];
        yield ['fungi', 'fungus'];
        yield ['furniture', 'furniture'];
        yield ['gallows', 'gallows'];
        yield ['gases', 'gas'];
        yield ['genevese', 'genevese'];
        yield ['genoese', 'genoese'];
        yield ['genera', 'genus'];
        yield ['gilbertese', 'gilbertese'];
        yield ['gloves', 'glove'];
        yield ['gold', 'gold'];
        yield ['geese', 'goose'];
        yield ['graves', 'grave'];
        yield ['gulfs', 'gulf'];
        yield ['halves', 'half'];
        yield ['hardware', 'hardware'];
        yield ['headquarters', 'headquarters'];
        yield ['heroes', 'hero'];
        yield ['herpes', 'herpes'];
        yield ['hijinks', 'hijinks'];
        yield ['hippopotami', 'hippopotamus'];
        yield ['hoaxes', 'hoax'];
        yield ['homework', 'homework'];
        yield ['horses', 'horse'];
        yield ['hottentotese', 'hottentotese'];
        yield ['houses', 'house'];
        yield ['humans', 'human'];
        yield ['identities', 'identity'];
        yield ['impatience', 'impatience'];
        yield ['indices', 'index'];
        yield ['information', 'information'];
        yield ['innings', 'innings'];
        yield ['irises', 'iris'];
        yield ['jackanapes', 'jackanapes'];
        yield ['jeans', 'jeans'];
        yield ['jedi', 'jedi'];
        yield ['kin', 'kin'];
        yield ['kiplingese', 'kiplingese'];
        yield ['kisses', 'kiss'];
        yield ['kitchenware', 'kitchenware'];
        yield ['knives', 'knife'];
        yield ['knowledge', 'knowledge'];
        yield ['kongoese', 'kongoese'];
        yield ['larvae', 'larva'];
        yield ['leaves', 'leaf'];
        yield ['leather', 'leather'];
        yield ['lenses', 'lens'];
        yield ['lives', 'life'];
        yield ['loaves', 'loaf'];
        yield ['lice', 'louse'];
        yield ['love', 'love'];
        yield ['lucchese', 'lucchese'];
        yield ['luggage', 'luggage'];
        yield ['mackerel', 'mackerel'];
        yield ['maltese', 'maltese'];
        yield ['men', 'man'];
        yield ['management', 'management'];
        yield ['matrices', 'matrix'];
        yield ['matrix_fus', 'matrix_fu'];
        yield ['matrix_rows', 'matrix_row'];
        yield ['media', 'medium'];
        yield ['memoranda', 'memorandum'];
        yield ['menus', 'menu'];
        yield ['messes', 'mess'];
        yield ['metadata', 'metadata'];
        yield ['mews', 'mews'];
        yield ['middleware', 'middleware'];
        yield ['money', 'money'];
        yield ['moose', 'moose'];
        yield ['mottoes', 'motto'];
        yield ['mice', 'mouse'];
        yield ['moves', 'move'];
        yield ['movies', 'movie'];
        yield ['mumps', 'mumps'];
        yield ['music', 'music'];
        yield ['my_analyses', 'my_analysis'];
        yield ['nankingese', 'nankingese'];
        yield ['neuroses', 'neurosis'];
        yield ['nexus', 'nexus'];
        yield ['niasese', 'niasese'];
        yield ['niveaux', 'niveau'];
        yield ['node_children', 'node_child'];
        yield ['nodemedia', 'nodemedia'];
        yield ['nuclei', 'nucleus'];
        yield ['nutrition', 'nutrition'];
        yield ['oases', 'oasis'];
        yield ['octopuses', 'octopus'];
        yield ['offspring', 'offspring'];
        yield ['oil', 'oil'];
        yield ['olives', 'olive'];
        yield ['oxen', 'ox'];
        yield ['pants', 'pants'];
        yield ['passes', 'pass'];
        yield ['passersby', 'passerby'];
        yield ['patience', 'patience'];
        yield ['pekingese', 'pekingese'];
        yield ['people', 'person'];
        yield ['perspectives', 'perspective'];
        yield ['photos', 'photo'];
        yield ['piedmontese', 'piedmontese'];
        yield ['pincers', 'pincers'];
        yield ['pistoiese', 'pistoiese'];
        yield ['plankton', 'plankton'];
        yield ['plateaux', 'plateau'];
        yield ['pliers', 'pliers'];
        yield ['pokemon', 'pokemon'];
        yield ['police', 'police'];
        yield ['polish', 'polish'];
        yield ['portfolios', 'portfolio'];
        yield ['portuguese', 'portuguese'];
        yield ['potatoes', 'potato'];
        yield ['powerhouses', 'powerhouse'];
        yield ['prizes', 'prize'];
        yield ['proceedings', 'proceedings'];
        yield ['processes', 'process'];
        yield ['progress', 'progress'];
        yield ['queries', 'query'];
        yield ['quizzes', 'quiz'];
        yield ['rabies', 'rabies'];
        yield ['radii', 'radius'];
        yield ['rain', 'rain'];
        yield ['reflexes', 'reflex'];
        yield ['research', 'research'];
        yield ['rhinoceros', 'rhinoceros'];
        yield ['rice', 'rice'];
        yield ['roofs', 'roof'];
        yield ['safes', 'safe'];
        yield ['salespeople', 'salesperson'];
        yield ['salmon', 'salmon'];
        yield ['sand', 'sand'];
        yield ['sarawakese', 'sarawakese'];
        yield ['saves', 'save'];
        yield ['scarves', 'scarf'];
        yield ['scissors', 'scissors'];
        yield ['scratches', 'scratch'];
        yield ['searches', 'search'];
        yield ['series', 'series'];
        yield ['sexes', 'sex'];
        yield ['shavese', 'shavese'];
        yield ['shears', 'shears'];
        yield ['sheep', 'sheep'];
        yield ['shelves', 'shelf'];
        yield ['shoes', 'shoe'];
        yield ['shorts', 'shorts'];
        yield ['sieves', 'sieve'];
        yield ['silk', 'silk'];
        yield ['skus', 'sku'];
        yield ['slices', 'slice'];
        yield ['sms', 'sms'];
        yield ['soap', 'soap'];
        yield ['socialmedia', 'socialmedia'];
        yield ['software', 'software'];
        yield ['spam', 'spam'];
        yield ['species', 'species'];
        yield ['splashes', 'splash'];
        yield ['spokesmen', 'spokesman'];
        yield ['spouses', 'spouse'];
        yield ['spies', 'spy'];
        yield ['stacks', 'stack'];
        yield ['stadia', 'stadium'];
        yield ['staff', 'staff'];
        yield ['statuses', 'status'];
        yield ['status_codes', 'status_code'];
        yield ['stimuli', 'stimulus'];
        yield ['stitches', 'stitch'];
        yield ['stories', 'story'];
        yield ['sugar', 'sugar'];
        yield ['swine', 'swine'];
        yield ['switches', 'switch'];
        yield ['syllabi', 'syllabus'];
        yield ['talent', 'talent'];
        yield ['taxes', 'tax'];
        yield ['taxis', 'taxi'];
        yield ['taxa', 'taxon'];
        yield ['termini', 'terminus'];
        yield ['testes', 'testis'];
        yield ['theses', 'thesis'];
        yield ['Thieves', 'Thief'];
        yield ['tights', 'tights'];
        yield ['tomatoes', 'tomato'];
        yield ['teeth', 'tooth'];
        yield ['toothpaste', 'toothpaste'];
        yield ['tornadoes', 'tornado'];
        yield ['traffic', 'traffic'];
        yield ['travel', 'travel'];
        yield ['trivia', 'trivia'];
        yield ['trousers', 'trousers'];
        yield ['trout', 'trout'];
        yield ['tries', 'try'];
        yield ['tuna', 'tuna'];
        yield ['valves', 'valve'];
        yield ['vermontese', 'vermontese'];
        yield ['vertices', 'vertex'];
        yield ['vinegar', 'vinegar'];
        yield ['viri', 'virus'];
        yield ['volcanoes', 'volcano'];
        yield ['wares', 'ware'];
        yield ['washes', 'wash'];
        yield ['watches', 'watch'];
        yield ['waves', 'wave'];
        yield ['weather', 'weather'];
        yield ['wenchowese', 'wenchowese'];
        yield ['wharves', 'wharf'];
        yield ['wheat', 'wheat'];
        yield ['whiting', 'whiting'];
        yield ['wives', 'wife'];
        yield ['wildebeest', 'wildebeest'];
        yield ['wishes', 'wish'];
        yield ['women', 'woman'];
        yield ['wood', 'wood'];
        yield ['wool', 'wool'];
        yield ['works', 'work'];
        yield ['yengeese', 'yengeese'];
        yield ['zombies', 'zombie'];
    }
}
