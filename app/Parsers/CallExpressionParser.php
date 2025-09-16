<?php

namespace App\Parsers;

use App\Contexts\AbstractContext;
use App\Contexts\MethodCall;
use App\Parser\Settings;
use Microsoft\PhpParser\MissingToken;
use Microsoft\PhpParser\Node\Expression\CallExpression;
use Microsoft\PhpParser\Node\QualifiedName;
use Microsoft\PhpParser\Node\SourceFileNode;
use Microsoft\PhpParser\Node\Statement\EmptyStatement;
use Microsoft\PhpParser\PositionUtilities;

class CallExpressionParser extends AbstractParser
{
    /**
     * @var MethodCall
     */
    protected AbstractContext $context;

    public function parse(CallExpression $node)
    {
        if ($this->context->methodName) {
            return $this->context;
        }

        if ($node->callableExpression instanceof QualifiedName) {
            $this->context->methodName = (string) ($node->callableExpression->getResolvedName() ?? $node->callableExpression->getText());
        }

        if (Settings::$capturePosition) {
            $range = PositionUtilities::getRangeFromPosition(
                $node->getStartPosition(),
                mb_strlen($node->getText()),
                $node->getRoot()->getFullText(),
            );

            if (Settings::$calculatePosition !== null) {
                $range = Settings::adjustPosition($range);
            }

            $this->context->setPosition($range);
        }

        $this->context->autocompleting = $node->closeParen instanceof MissingToken;

        // Temporary fix for @for/@foreach directive. I don't know why,
        // but parser always returns closeParen = MissingToken for @foreach($examples as $example)
        $parent = $node->getParent()?->getParent();

        if ($parent instanceof SourceFileNode) {
            $statements = $parent->statementList;

            $lastStatement = end($statements);

            $this->context->autocompleting &= !$lastStatement instanceof EmptyStatement;
        }

        return $this->context;
    }

    public function initNewContext(): ?AbstractContext
    {
        // TODO: Unclear if this is correct
        if (!($this->context instanceof MethodCall) || $this->context->touched()) {
            return new MethodCall;
        }

        return null;
    }
}
