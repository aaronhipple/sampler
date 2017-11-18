<?php 

/**
 * SampleIterator iterates over samples.
 *
 * @category Testing
 * @package  AaronHipple\Sampler
 * @author   Aaron Hipple <ahipple@gmail.com>
 * @license  https://github.com/aaronhipple/sampler/blob/master/LICENSE (MIT)
 * @link     https://github.com/aaronhipple/sampler
 */

namespace AaronHipple\Sampler;

use Iterator;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\ParserFactory;

/**
 * SampleIterator iterates over samples.
 *
 * This was meant to allow us to more efficiently pass doc samples
 * into PHPUnit but apparently they just call `iterator_to_array`
 * on the thing anyway. Maybe it'll be useful some day.
 *
 * @category Testing
 * @package  AaronHipple\Sampler
 * @author   Aaron Hipple <ahipple@gmail.com>
 * @license  https://github.com/aaronhipple/sampler/blob/master/LICENSE (MIT)
 * @link     https://github.com/aaronhipple/sampler
 */
class SampleIterator implements Iterator
{
    /**
     * Paths to scan.
     *
     * @var []string
     */
    protected $paths = [];
    
    /**
     * Extensions to scan.
     *
     * @var []string
     */
    protected $extensions = [];
    
    /**
     * Files to scan.
     *
     * @var []string
     */
    protected $files = [];

    /**
     * Current index among the files to be scanned.
     *
     * @var int
     */
    protected $fileIndex = 0;
    
    /**
     * Current index among the currently-loaded samples.
     *
     * @var int
     */
    protected $sampleIndex = 0;

    /**
     * Loaded samples for the current file.
     *
     * @var []string
     */
    protected $fileSamples = [];
  
    /**
     * Create a sample iterator.
     *
     * @param []string $paths      Directories to scan.
     * @param []string $extensions File extensions to scan.
     */
    public function __construct(array $paths, array $extensions = ['php']) 
    {
        $this->paths = $paths;
        $this->extensions = $extensions;
        $this->files = $this->files();
    }

    /**
     * Rewind the iterator.
     *
     * @return void
     */
    public function rewind() 
    {
        $this->fileIndex = 0;
        $this->sampleIndex = 0;
        $this->fileSamples = [];
        $this->setFileSamples();
    }

    /**
     * Get the current value from the iterator.
     *
     * @return string A sample to be executed.
     */
    public function current() 
    {
        return [array_values($this->fileSamples)[$this->sampleIndex]];
    }
    
    /**
     * Get the current key from the iterator.
     *
     * @return string A sample key.
     */
    public function key() 
    {
        return array_keys($this->fileSamples)[$this->sampleIndex];
    }

    /**
     * Set the next position.
     *
     * @return void
     */
    public function next() 
    {
        // If we're still iterating over a set of samples...
        if (++$this->sampleIndex < count($this->fileSamples)) {
            return;
        }

        // We're out of samples, try the next file(s) until we get more.
        $this->fileSamples = [];
        while (empty($this->fileSamples) && ++$this->fileIndex < count($this->files)) {
            $this->setFileSamples();
        }
    }

    /**
     * Is the current position valid?
     *
     * @return bool
     */
    public function valid()
    {
        $hasFile = $this->fileIndex < count($this->files);
        $hasSamples = $this->sampleIndex < count($this->fileSamples);
        return $hasFile && $hasSamples;
    }

    /**
     * A list of files to scan for sample tests.
     *
     * @return []string
     */
    protected function files()
    {
        return array_merge(
            ...array_map(
                [$this, 'glob'], $this->paths
            )
        );
    }

    /**
     * Return a list of files from the given path.
     *
     * @param string $path A path to a directory.
     *
     * @return []string
     */
    protected function glob($path) 
    {
        return glob(
            realpath($path) . '/{**/*,*}.{' . implode(',', $this->extensions) . '}',
            GLOB_BRACE
        );
    }

    /**
     * Get docblocks from a given file.
     *
     * @return []string
     */
    protected function setFileSamples() 
    {
        $this->sampleIndex = 0;
        
        $file = $this->files[$this->fileIndex];

        if (empty($file)) {
            return;
        }

        include_once $file;

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $extractor = new SampleExtractor();
        $traverser = new NodeTraverser();

        $traverser->addVisitor(new NodeVisitor\NameResolver());
        $traverser->addVisitor($extractor);

        $code = file_get_contents($file);
        $ast = $parser->parse($code);
        $traverser->traverse($ast);

        $this->fileSamples = $extractor->getSamples();
    }
}
