<?php namespace AaronHipple\Sampler\Example;

/**
 * Generate some extra excitement.
 *
 * @param string $sentence A sentence.
 *
 * @return string A very excited sentence.
 *
 * @sample
 *   use AaronHipple\Sampler\Example;
 *   use PHPUnit\Framework\Assert;
 *
 *   Assert::assertEquals(
 *     'A boring sentence!',
 *     Example\excite("A boring sentence.")
 *   );
 * @sample
 *   use AaronHipple\Sampler\Example;
 *   use PHPUnit\Framework\Assert;
 *
 *   $greeter = new Example\Greeter();
 *
 *   Assert::assertEquals(
 *     'Hello, Aaron!',
 *     Example\excite($greeter->greet('Aaron'))
 *   );
 */
function excite($sentence) 
{
    return preg_replace(
        '/\.$/',
        '!',
        $sentence
    );
}

// I don't have a doc comment!
function bore($sentence) 
{
    return preg_replace(
        '/\!$/',
        '.',
        $sentence
    );
}
