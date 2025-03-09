<?php

namespace App\Parsers;

use App\Contexts\AbstractContext;
use App\Contexts\MethodCall;

class ArgumentExpressionListParser extends AbstractParser
{
    /**
     * @var MethodCall
     */
    protected AbstractContext $context;

    public function parse($node)
    {
        // Temporary fix. I don't know why foreach directive has autocompleting true as default
        $this->context->autocompleting = false;

        if ($this->context instanceof MethodCall) {
            return $this->context->arguments;
        }

        return $this->context;
    }
}
