<?php

declare(strict_types=1);

namespace Rector\NetteCodeQuality\Rector\ArrayDimFetch;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NetteCodeQuality\NodeResolver\FormVariableInputNameTypeResolver;

/**
 * @sponsor Thanks https://amateri.com for sponsoring this rule - visit them on https://www.startupjobs.cz/startup/scrumworks-s-r-o
 *
 * @see \Rector\NetteCodeQuality\Tests\Rector\ArrayDimFetch\ChangeFormArrayAccessToAnnotatedControlVariableRector\ChangeFormArrayAccessToAnnotatedControlVariableRectorTest
 */
final class ChangeFormArrayAccessToAnnotatedControlVariableRector extends AbstractArrayDimFetchToAnnotatedControlVariableRector
{
    /**
     * @var FormVariableInputNameTypeResolver
     */
    private $formVariableInputNameTypeResolver;

    public function __construct(FormVariableInputNameTypeResolver $formVariableInputNameTypeResolver)
    {
        $this->formVariableInputNameTypeResolver = $formVariableInputNameTypeResolver;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Change array access magic on $form to explicit standalone typed variable', [
            new CodeSample(
                <<<'PHP'
use Nette\Application\UI\Form;

class SomePresenter
{
    public function run()
    {
        $form = new Form();
        $this->addText('email', 'Email');

        $form['email']->value = 'hey@hi.hello';
    }
}
PHP
,
                <<<'PHP'
use Nette\Application\UI\Form;

class SomePresenter
{
    public function run()
    {
        $form = new Form();
        $this->addText('email', 'Email');

        /** @var \Nette\Forms\Controls\TextInput $emailControl */
        $emailControl = $form['email'];
        $emailControl->value = 'hey@hi.hello';
    }
}
PHP

            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ArrayDimFetch::class];
    }

    /**
     * @param ArrayDimFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        $inputName = $this->controlDimFetchAnalyzer->matchName($node);
        if ($inputName === null) {
            return null;
        }

        if ($this->isBeingAssignedOrInitialized($node)) {
            return null;
        }

        $controlVariableName = $this->netteControlNaming->createVariableName($inputName);

        // 1. find previous calls on variable
        /** @var Variable $formVariable */
        $formVariable = $node->var;

        $controlType = $this->formVariableInputNameTypeResolver->resolveControlTypeByInputName(
            $formVariable,
            $inputName
        );

        $formVariableName = $this->getName($formVariable);
        if ($formVariableName === null) {
            throw new ShouldNotHappenException();
        }

        $controlObjectType = new ObjectType($controlType);

        $this->addAssignExpressionForFirstCase($controlVariableName, $node, $controlObjectType);

        return new Variable($controlVariableName);
    }
}
