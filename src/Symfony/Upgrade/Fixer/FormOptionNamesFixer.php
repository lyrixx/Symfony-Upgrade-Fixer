<?php

namespace Symfony\Upgrade\Fixer;

use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * @todo The options “csrf_provider” and “intention” were renamed to “csrf_token_generator“ and “csrf_token_id”
 */
class FormOptionNamesFixer extends FormTypeFixer
{
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        if ($this->isFormType($tokens)) {
            $this->fixOptionNames($tokens, 'precision', 'scale');
            $this->fixOptionNames($tokens, 'virtual', 'inherit_data');
        }

        return $tokens->generateCode();
    }

    public function getDescription()
    {
        return 'Options precision and virtual was renamed to scale and inherit_data.';
    }

    private function fixOptionNames(Tokens $tokens, $oldName, $newName, $start = 0)
    {
        $matchedTokens = $tokens->findSequence([
            [T_OBJECT_OPERATOR],
            [T_STRING, 'add'],
            '(',
            [T_CONSTANT_ENCAPSED_STRING],
            ',',
            [T_CONSTANT_ENCAPSED_STRING],
            ',',
        ], $start);

        if (null === $matchedTokens) {
            return;
        }

        $isArray = $tokens->isArray(
            $index = $tokens->getNextMeaningfulToken(
                end(array_keys($matchedTokens))
            )
        );

        if (!$isArray) {
            return;
        }

        do {
            $index = $tokens->getNextMeaningfulToken($index);
            $token = $tokens[$index];

            if (!$token->isGivenKind(T_CONSTANT_ENCAPSED_STRING)) {
                continue;
            }

            if ("'$oldName'" === $token->getContent()) {
                $token->setContent("'$newName'");
            }
        } while (!in_array($token->getContent(), [')', ']']) );

        $this->fixOptionNames($tokens, $oldName, $newName, $index);
    }
}
