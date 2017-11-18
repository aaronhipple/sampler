<?php namespace AaronHipple\Sampler\Example;

use PHPUnit\Framework\TestCase;

class Greeter
{
    /**
     * Returns a greeting.
     *
     * @param string $name A person's name.
     *
     * @return string A greeting.
     *
     * @sample
     *   $greeter = new AaronHipple\Sampler\Example\Greeter();
     *   PHPUnit\Framework\Assert::assertEquals(
     *     'Hello, Aaron!',
     *     $greeter->greet('Aaron')
     *   );
     */
    public function greet($name) 
    {
        return "Hello, {$name}!";
    }
}
