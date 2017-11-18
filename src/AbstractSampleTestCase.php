<?php 

/**
 * PHP version 7
 *
 * @category Testing
 * @package  AaronHipple\Sampler
 * @author   Aaron Hipple <ahipple@gmail.com>
 * @license  https://github.com/aaronhipple/sampler/blob/master/LICENSE (MIT)
 * @link     https://github.com/aaronhipple/sampler
 */

namespace AaronHipple\Sampler;

use PHPUnit\Framework\TestCase;

/**
 * SampleTestCase tests samples.
 *
 * @category Testing
 * @package  AaronHipple\Sampler
 * @author   Aaron Hipple <ahipple@gmail.com>
 * @license  https://github.com/aaronhipple/sampler/blob/master/LICENSE (MIT)
 * @link     https://github.com/aaronhipple/sampler
 */
abstract class AbstractSampleTestCase extends TestCase
{
    /**
     * Paths from which to extract samples.
     *
     * @return []string An array of directory paths.
     */
    abstract protected function paths();

    /**
     * Extensions from which to extract samples.
     *
     * @return []string An array of file extensions.
     */
    protected function extensions() 
    {
        return ['php'];
    }

    /**
     * Evaluate a sample.
     *
     * @param string $code Sample code to be tested.
     *
     * @return void
     *
     * @todo Wrap failures in some way to provide more useful information.
     * 
     * @dataProvider sampleProvider
     */
    public function testSample($code)
    {
        echo eval($code);
    }

    /**
     * Provide a sample code iterator.
     *
     * @return SampleIterator
     */
    public function sampleProvider()
    {
        return iterator_to_array(
            new SampleIterator(
                $this->paths(),
                $this->extensions()
            ), true
        );
    }
}
