<?php
/**
 * SampleExtractor extracts samples from a given AST.
 *
 * PHP version 7
 *
 * @category Testing
 * @package  AaronHipple\Sampler
 * @author   Aaron Hipple <ahipple@gmail.com>
 * @license  https://github.com/aaronhipple/sampler/blob/master/LICENSE (MIT)
 * @link     https://github.com/aaronhipple/sampler
 */

namespace AaronHipple\Sampler;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use phpDocumentor\Reflection\DocBlockFactory;

/**
 * SampleExtractor extracts samples from a given AST.
 *
 * @category Testing
 * @package  AaronHipple\Sampler
 * @author   Aaron Hipple <ahipple@gmail.com>
 * @license  https://github.com/aaronhipple/sampler/blob/master/LICENSE (MIT)
 * @link     https://github.com/aaronhipple/sampler
 */
class SampleExtractor extends NodeVisitorAbstract
{
    /**
     * Extracted samples.
     *
     * @var []string
     */
    protected $samples = [];

    /**
     * Extract sample tests from certain nodes.
     *
     * @param Node $node A node in the AST.
     *
     * @return void
     */
    public function leaveNode(Node $node) 
    {
        if ($node instanceof Node\Stmt\Class_) {
            $this->getClassSamples($node);
            $this->getClassMethodSamples($node);
        }
        
        if ($node instanceof Node\Stmt\Function_) {
            $this->getFunctionSamples($node);
        }
    }

    /**
     * Get samples from a given class.
     *
     * @param Node\Stmt\Class_ $class A 'class' node in the AST.
     *
     * @return void
     */
    protected function getClassSamples(Node\Stmt\Class_ $class) 
    {
        $classComment = $class->getDocComment();
        if (!empty($classComment)) {
            $classSamples = $this->extractSamplesFromDocBlock(
                $classComment->getReformattedText()
            );
            
            array_walk(
                $classSamples, function ($sample, $index) use ($class) {
                    $key = sprintf(
                        '%s #%d',
                        $class->namespacedName,
                        $index
                    );
                    $this->samples[$key] = $sample;
                }
            );
        }
    }

    /**
     * Get samples for each method of a given class.
     *
     * @param Node\Stmt\Class_ $class A 'class' node in the AST.
     *
     * @return void
     */
    protected function getClassMethodSamples(Node\Stmt\Class_  $class) 
    {
        foreach ($class->getMethods() as $method) {
            $methodComment = $method->getDocComment();

            if (empty($methodComment)) {
                return;
            }
            
            $methodSamples = $this->extractSamplesFromDocBlock(
                $methodComment->getReformattedText()
            );

            array_walk(
                $methodSamples, function ($sample, $index) use ($class, $method) {
                    $key = sprintf(
                        '%s::%s #%d',
                        $class->namespacedName,
                        $method->name,
                        $index
                    );
                    $this->samples[$key] = $sample;
                }
            );
        }
    }
    
    /**
     * Get samples from a given function.
     *
     * @param Node\Stmt\function_ $function A 'function' node in the AST.
     *
     * @return void
     */
    protected function getFunctionSamples(Node\Stmt\Function_ $function) 
    {
        $functionComment = $function->getDocComment();

        if (empty($functionComment)) {
            return;
        }
        
        $samples = $this->extractSamplesFromDocBlock(
            $functionComment->getReformattedText()
        );

        array_walk(
            $samples, function ($sample, $index) use ($function) {
                $key = sprintf(
                    '%s #%d',
                    $function->namespacedName,
                    $index
                );
                $this->samples[$key] = $sample;
            }
        );
    }

    /**
     * Extract sample code.
     *
     * @param string $block A documentation block.
     *
     * @return []string
     */
    protected function extractSamplesFromDocBlock($block) 
    {
        if (!isset($this->_factory)) {
            $this->_factory = DocBlockFactory::createInstance();
        }

        $docblock = $this->_factory->create($block);
        $tags = $docblock->getTagsByName('sample');

        return array_map(
            function ($tag) {
                return (string)$tag;
            }, $tags
        );
    }

    /**
     * Retrieve a list of extracted samples.
     *
     * @return []string
     */
    public function getSamples() 
    {
        return $this->samples;
    }
}
