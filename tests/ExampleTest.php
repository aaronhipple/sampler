<?php

use AaronHipple\Sampler\AbstractSampleTestCase;

class ExampleTest extends AbstractSampleTestCase
{
    protected function paths() 
    {
        return [__DIR__ . '/../example'];
    }
    
    protected function extensions() 
    {
        return ['php', 'inc'];
    }
}
