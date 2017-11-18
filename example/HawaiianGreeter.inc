<?php namespace AaronHipple\Sampler\Example;

use PHPUnit\Framework\TestCase;

/**
 * Greets people the Hawaiian way.
 
 * ```php
 * $greeter = new AaronHipple\Sampler\Example\HawaiianGreeter();
 * PHPUnit\Framework\Assert::assertEquals(
 *   'Aloha, Frank!',
 *   $greeter->greet('Frank')
 * );
 * ```
 */
class HawaiianGreeter
{
    /**
     * Returns a greeting.
     *
     * @param string $name A person's name.
     *
     * @return string A greeting.
     *
     * @sample
     *   $greeter = new AaronHipple\Sampler\Example\HawaiianGreeter();
     *   PHPUnit\Framework\Assert::assertEquals(
     *     'Aloha, Aaron!',
     *     $greeter->greet('Aaron')
     *   );
     */
    public function greet($name) 
    {
        return $this->reallyGreet($name);
    }
    
    /**
     * I have an empty sample.
     *
     * @param string $name A person's name.
     *
     * @return string A greeting.
     *
     * @sample
     */
    protected function reallyGreet($name) 
    {
        return "Aloha, {$name}!";
    }
}
