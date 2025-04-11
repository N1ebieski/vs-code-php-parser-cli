<?php

namespace App\Parsers;

use App\Contexts\AbstractContext;
use App\Contexts\Blade;
use App\Parser\Parse;
use App\Parser\Settings;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Microsoft\PhpParser\Node\Statement\InlineHtml;
use Microsoft\PhpParser\Parser;
use Microsoft\PhpParser\PositionUtilities;
use Microsoft\PhpParser\Range;
use Stillat\BladeParser\Document\Document;
use Stillat\BladeParser\Nodes\BaseNode;
use Stillat\BladeParser\Nodes\Components\ComponentNode;
use Stillat\BladeParser\Nodes\Components\ParameterNode;
use Stillat\BladeParser\Nodes\DirectiveNode;
use Stillat\BladeParser\Nodes\EchoNode;
use Stillat\BladeParser\Nodes\EchoType;
use Stillat\BladeParser\Nodes\LiteralNode;

class InlineHtmlParser extends AbstractParser
{
    protected $echoStrings = [
        '{!!' => '!!}',
        '{{{' => '}}}',
        '{{'  => '}}',
    ];

    protected $startLine = 0;

    /**
     * @var Blade
     */
    protected AbstractContext $context;

    protected array $items = [];

    /**
     * There is a bug with the Stillat\BladeParser\Document\Document::fromText parser.
     * It doesn't parse the special characters correctly. It treats these characters
     * as indentations and spaces resulting in a miscalculated Node position.
     *
     * This function replaces the special characters with a single, placeholder character
     */
    private function replaceSpecialAndEmoji(string $text, string $placeholder = '*'): string
    {
        return preg_replace(
            '/[\x{3040}-\x{30FF}\x{4E00}-\x{9FFF}\x{1F300}-\x{1FAFF}\x{1F000}-\x{1F6FF}\x{2190}-\x{21AA}\x{2300}-\x{23FF}\x{25A0}-\x{25FF}\x{1F600}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{2700}-\x{27BF}]/u',
            $placeholder,
            $text
        );
    }

    public function parse(InlineHtml $node)
    {
        if ($node->getStartPosition() > 0) {
            $range = PositionUtilities::getRangeFromPosition(
                $node->getStartPosition(),
                mb_strlen($node->getText()),
                $node->getRoot()->getFullText(),
            );

            $this->startLine = $range->start->line;
        }

        $this->parseBladeContent(Document::fromText(
            $this->replaceSpecialAndEmoji($node->getText())
        ));

        if (count($this->items)) {
            $blade = new Blade;
            $this->context->initNew($blade);

            $blade->children = $this->items;

            return $blade;
        }

        return $this->context;
    }

    protected function parseBladeContent($node)
    {
        foreach ($node->getNodes() as $child) {
            // TODO: Add other echo types as well
            if ($child instanceof LiteralNode) {
                $this->parseLiteralNode($child);
            }

            if ($child instanceof DirectiveNode) {
                $this->parseBladeDirective($child);
            }

            if ($child instanceof EchoNode) {
                $this->parseEchoNode($child);
            }

            if ($child instanceof ComponentNode) {
                $this->parseComponentNode($child);
            }

            $this->parseBladeContent($child);
        }
    }

    protected function parseComponentNode(ComponentNode $node)
    {
        /** @var Collection<int, ParameterNode> $parameters */
        $parameters = $node->getParameters();

        foreach ($parameters as $parameter) {
            $prefix = Str::match('/^({{{|{{|{!!)/', $parameter->value);

            $snippet = "<?php\n" . str_repeat(' ', $node->getStartIndentationLevel()) . str_replace($prefix, '', $parameter->value) . ';';

            $sourceFile = (new Parser)->parseSourceFile($snippet);

            Settings::$calculatePosition = function (Range $range) use ($node, $parameter) {
                if ($range->start->line === 1) {
                    $rangeCharacters = $range->end->character - $range->start->character;
                    // If component has />, then we need to remove 1 character
                    $selfClosingCharacter = $node->getIsSelfClosing() ? 1 : 0;

                    $firstQuotePosition = strpos($parameter->content, "'");

                    $range->start->character = $parameter->position->startColumn + $firstQuotePosition - $selfClosingCharacter;
                    $range->end->character = $parameter->position->startColumn + $firstQuotePosition + $rangeCharacters - $selfClosingCharacter;
                }

                $range->start->line += $this->startLine + $parameter->position->startLine - 2;
                $range->end->line += $this->startLine + $parameter->position->startLine - 2;

                return $range;
            };

            $result = Parse::parse($sourceFile);

            if (count($result->children) === 0) {
                continue;
            }

            $child = $result->children[0];

            $this->items[] = $child;
        }
    }

    protected function doEchoParse(BaseNode $node, $prefix, $content)
    {
        $snippet = "<?php\n" . str_repeat(' ', $node->getStartIndentationLevel()) . str_replace($prefix, '', $content) . ';';

        $sourceFile = (new Parser)->parseSourceFile($snippet);

        $suffix = $this->echoStrings[$prefix];

        Settings::$calculatePosition = function (Range $range) use ($node, $prefix, $suffix) {
            if ($range->start->line === 1) {
                $range->start->character += mb_strlen($prefix);
                $range->end->character += mb_strlen($suffix);
            }

            $range->start->line += $this->startLine + $node->position->startLine - 2;
            $range->end->line += $this->startLine + $node->position->startLine - 2;

            return $range;
        };

        $result = Parse::parse($sourceFile);

        if (count($result->children) === 0) {
            return;
        }

        $child = $result->children[0];

        $this->items[] = $child;
    }

    protected function parseLiteralNode(LiteralNode $node)
    {
        foreach ($this->echoStrings as $prefix => $suffix) {
            if (!str_starts_with($node->content, $prefix)) {
                continue;
            }

            $this->doEchoParse($node, $prefix, $node->content);
        }
    }

    protected function parseBladeDirective(DirectiveNode $node)
    {
        $content = $node->toString();

        if (!$node->hasArguments()) {
            $content .= "('";
        }

        if ($node->isClosingDirective) {
            return;
        }

        $methodUsed = '@' . $node->content;
        $safetyPrefix = 'directive';
        $snippet = "<?php\n" . str_repeat(' ', $node->getStartIndentationLevel()) . str_replace($methodUsed, $safetyPrefix . $node->content, $content . ';');

        $sourceFile = (new Parser)->parseSourceFile($snippet);

        Settings::$calculatePosition = function (Range $range) use ($node, $safetyPrefix) {
            if ($range->start->line === 1) {
                $range->start->character -= mb_strlen($safetyPrefix) - 1;
                $range->end->character -= mb_strlen($safetyPrefix) - 1;
            }

            $range->start->line += $this->startLine + $node->position->startLine - 2;
            $range->end->line += $this->startLine + $node->position->startLine - 2;

            return $range;
        };

        $result = Parse::parse($sourceFile);

        if (count($result->children) === 0) {
            return;
        }

        $child = $result->children[0];

        $child->methodName = '@' . substr($child->methodName, mb_strlen($safetyPrefix));

        $this->items[] = $child;
    }

    protected function parseEchoNode(EchoNode $node)
    {
        $prefix = match ($node->type) {
            EchoType::RawEcho    => '{!!',
            EchoType::TripleEcho => '{{{',
            default              => '{{',
        };

        $this->doEchoParse($node, $prefix, $node->innerContent);
    }
}
