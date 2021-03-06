<?php

namespace Symfony\Upgrade\Fixer;

use Symfony\CS\AbstractFixer as BaseAbstractFixer;
use Symfony\CS\Tokenizer\Tokens;

abstract class AbstractFixer extends BaseAbstractFixer
{
    protected function hasUseDeclarations(Tokens $tokens, array $fqcn)
    {
        return null !== $this->getUseDeclarations($tokens, $fqcn);
    }

    protected function getUseDeclarations(Tokens $tokens, array $fqcn)
    {
        $sequence = [[T_USE]];

        foreach ($fqcn as $component) {
            $sequence = array_merge(
                $sequence,
                [[T_STRING, $component], [T_NS_SEPARATOR]]
            );
        }

        $sequence[count($sequence) - 1] = ';';

        return $tokens->findSequence($sequence);
    }
}
