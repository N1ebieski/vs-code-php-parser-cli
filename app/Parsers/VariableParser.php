<?php

namespace App\Parsers;

use App\Contexts\AbstractContext;
use App\Contexts\Variable as VariableContext;
use App\Parser\Settings;
use Microsoft\PhpParser\Node\Expression\Variable;
use Microsoft\PhpParser\PositionUtilities;

class VariableParser extends AbstractParser
{
    /**
     * @var VariableContext
     */
    protected AbstractContext $context;

    /**
     * Check if the variable has a object operator and
     * is a last element in the string
     */
    private function isAutocompleting(Variable $node): bool
    {
        return preg_match('/\$' . $node->getName() . '->;$/s', $node->getFileContents());
    }

    public function parse(Variable $node)
    {
        if ($this->isAutocompleting($node)) {
            $this->context->autocompleting = true;
        }

        $this->context->name = $node->getName();

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

        return $this->context;
    }

    public function initNewContext(): ?AbstractContext
    {
        return new VariableContext;
    }
}
