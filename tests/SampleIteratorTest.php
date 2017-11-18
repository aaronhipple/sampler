<?php

use PHPUnit\Framework\TestCase;
use AaronHipple\Sampler\SampleIterator;

class SampleIteratorTest extends TestCase
{
    /**
     * SampleIterator is an instance of iterator.
     *
     * @return void
     */
    public function testSampleIteratorHasKeys()
    {
        $samples = new SampleIterator(
            [__DIR__ . '/../example'],
            ['php', 'inc']
        );

        $samplesAsArray = iterator_to_array($samples, TRUE);

        $this->assertArrayHasKey('AaronHipple\Sampler\Example\extra\PirateGreeter::greet #0', $samplesAsArray);
        $this->assertArrayHasKey('AaronHipple\Sampler\Example\Greeter::greet #0', $samplesAsArray);
        $this->assertArrayHasKey('AaronHipple\Sampler\Example\HawaiianGreeter::greet #0', $samplesAsArray);
        $this->assertArrayHasKey('AaronHipple\Sampler\Example\excite #0', $samplesAsArray);
    }
}
