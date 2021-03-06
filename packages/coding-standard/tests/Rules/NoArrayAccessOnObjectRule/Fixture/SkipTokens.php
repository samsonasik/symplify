<?php

declare(strict_types=1);

namespace Symplify\CodingStandard\Tests\Rules\NoArrayAccessOnObjectRule\Fixture;

use PhpCsFixer\Tokenizer\Tokens;

final class SkipTokens
{
    public function run(Tokens $tokens)
    {
        return $tokens['key'];
    }
}
