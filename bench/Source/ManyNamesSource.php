<?php

declare(strict_types=1);

namespace Rector\Bench\Source;

use Countable;
use DateTime;
use DateTimeImmutable;

/**
 * Fixture parsed by short_name_resolver_bench.php. Deliberately name-heavy:
 * many fully-qualified references plus docblock types, so resolveFromFile() does real work.
 * References only real classes so it stays valid if any whole-repo check scans it.
 */
final class ManyNamesSource implements Countable
{
    /**
     * @var \PhpParser\Node\Stmt\Class_[]
     */
    private array $classes = [];

    /**
     * @param \PhpParser\Node\Name $name
     * @return \PhpParser\Node\Expr\New_
     */
    public function first(\PhpParser\Node\Name $name): \PhpParser\Node\Expr\New_
    {
        $a = new \PhpParser\Node\Scalar\String_('a');
        $b = new \PhpParser\Node\Identifier('b');
        $c = new DateTimeImmutable();

        return new \PhpParser\Node\Expr\New_($name);
    }

    /**
     * @param \PhpParser\Node\Stmt\Property $property
     * @return \PhpParser\Comment\Doc
     */
    public function second(\PhpParser\Node\Stmt\Property $property): \PhpParser\Comment\Doc
    {
        $x = new \PhpParser\Node\Stmt\ClassMethod('x');
        $y = new \SplStack();
        $z = new \ArrayObject();

        return new \PhpParser\Comment\Doc('/** */');
    }

    /**
     * @param \PhpParser\Node\Arg $arg
     * @return \PhpParser\Node\Expr\MethodCall
     */
    public function third(\PhpParser\Node\Arg $arg): \PhpParser\Node\Expr\MethodCall
    {
        $p = new \RuntimeException();
        $q = new \LogicException();
        $r = new DateTime();

        return new \PhpParser\Node\Expr\MethodCall(new \PhpParser\Node\Expr\Variable('v'), 'm');
    }

    /**
     * @param \PhpParser\Node\Stmt\Use_ $use
     * @return \PhpParser\Node\Stmt\Namespace_
     */
    public function fourth(\PhpParser\Node\Stmt\Use_ $use): \PhpParser\Node\Stmt\Namespace_
    {
        $s = new \SplObjectStorage();
        $t = new \DateInterval('P1D');
        $u = new \PhpParser\Node\Scalar\Int_(1);

        return new \PhpParser\Node\Stmt\Namespace_(new \PhpParser\Node\Name('N'));
    }

    public function count(): int
    {
        return count($this->classes);
    }
}
