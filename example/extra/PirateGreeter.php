<?php namespace AaronHipple\Sampler\Example\extra;

use PHPUnit\Framework\TestCase;

class PirateGreeter
{
    /**
     * Returns a greeting.
     *
     * @param string $name A person's name.
     *
     * @return string A greeting.
     *
     * @sample
     *   $greeter = new AaronHipple\Sampler\Example\extra\PirateGreeter();
     *   PHPUnit\Framework\Assert::assertEquals(
     *     'Avast, Aaron!',
     *     $greeter->greet('Aaron')
     *   );
     */
    public function greet($name) 
    {
        return $this->reallyGreet($name);
    }
    
    protected function reallyGreet($name) 
    {
        return "Avast, {$name}!";
    }
}
