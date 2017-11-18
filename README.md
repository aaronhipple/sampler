# sampler
[![Latest Stable Version](https://poser.pugx.org/aaronhipple/sampler/v/stable)](https://packagist.org/packages/aaronhipple/sampler)
[![Build Status](https://travis-ci.org/aaronhipple/sampler.svg?branch=master)](https://travis-ci.org/aaronhipple/sampler)
[![Maintainability](https://api.codeclimate.com/v1/badges/d0b8fc607863ad444c85/maintainability)](https://codeclimate.com/github/aaronhipple/sampler/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/d0b8fc607863ad444c85/test_coverage)](https://codeclimate.com/github/aaronhipple/sampler/test_coverage)

Test inline code samples from your docblocks using PHPUnit!

## caveats
- This is not particularly mature. Issues and pull requests welcome.
- The extracted sample code is `eval`'d for testing, with all those security
  implications. As always, be mindful only to run trusted code in sensitive
  environments.
- Right now it only works on classes, class methods, and functions. It does no
  magic to expose private or protected methods, so normal rules apply.

## usage

First, install `sampler` as a dependency.

```bash
$ composer require aaronhipple/sampler
```

Then, in your PHPUnit suite, implement a case extending `AbstractSampleTestCase`.

```php
use AaronHipple\Sampler\AbstractSampleTestCase;

class SampleTest extends AbstractSampleTestCase
{
    /**
     * Provide absolute paths to the directories to scan for samples.
     * 
     * @return []string An array of folder paths.
     */
    protected function paths() 
    {
        return [__DIR__ . '/../src'];
    }
   
    /**
     * (Optional) Provide a list of file extensions to scan.
     */
    protected function extensions() 
    {
        return ['php', 'inc'];
    }
}
```

Finally, write some samples!

```php
/**
 * Say hello to someone!
 *
 * @sample
 *   use PHPUnit\Framework\Assert;
 *   Assert::assertInternalType(
 *     'string',
 *     say_hello('Frank')
 *   );
 * @sample
 *   use PHPUnit\Framework\Assert;
 *   Assert::assertEquals(
 *     'Hello, Aaron!',
 *     say_hello('Aaron')
 *   );
 */
function say_hello($name) {
  return "Hello, $name!";
}
```

Run your test suite as usual, with `phpunit`.

## contributing

- Pull requests are welcome.
- More tests please.
- PSR-2 is good. Use `phpcbf`.
