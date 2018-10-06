<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;
use TRegx\CleanRegex\Internal\Delimiter\ExplicitDelimiterRequiredException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\FlagsValidator;

class DelimitererTest extends TestCase
{
    public function patternsAndResults()
    {
        return [
            ['siema', '/siema/'],
            ['sie#ma', '/sie#ma/'],
            ['sie/ma', '#sie/ma#'],
            ['si/e#ma', '%si/e#ma%'],
            ['si/e#m%a', '~si/e#m%a~'],
            ['s~i/e#m%a', '+s~i/e#m%a+'],
            ['s~i/e#++m%a', '!s~i/e#++m%a!'],
            ['s~i/e#++m%a!', '@s~i/e#++m%a!@'],
            ['s~i/e#++m%a!@', '_s~i/e#++m%a!@_'],
            ['s~i/e#++m%a!@_', ';s~i/e#++m%a!@_;'],
            ['s~i/e#++m%a!@_;', '`s~i/e#++m%a!@_;`'],
            ['s~i/e#++m%a!@_;`', '-s~i/e#++m%a!@_;`-'],
            ['s~i/e#++m%a!@_;`-', '=s~i/e#++m%a!@_;`-='],
        ];
    }

    /**
     * @test
     * @dataProvider patternsAndResults
     * @param string $pattern
     * @param string $expectedResult
     */
    public function shouldDelimiterPattern(string $pattern, string $expectedResult)
    {
        // given
        $delimiterer = new Delimiterer(new DelimiterParser(new FlagsValidator()));

        // when
        $result = $delimiterer->delimiter($pattern);

        // then
        $this->assertEquals($expectedResult, $result);
    }

    public function alreadyDelimitered()
    {
        return [
            ['/a/'],
            ['#a#'],
            ['%a%'],
            ['~a~'],
            ['+a+'],
            ['!a!'],
        ];
    }

    /**
     * @test
     * @dataProvider alreadyDelimitered
     * @param string $pattern
     */
    public function shouldDelimiterAlreadyDelimitered(string $pattern)
    {
        // given
        $delimiterer = new Delimiterer(new DelimiterParser(new FlagsValidator()));

        // when
        $result = $delimiterer->delimiter($pattern);

        // then
        $this->assertEquals($pattern, $result);
    }

    /**
     * @test
     */
    public function shouldThrowOnNotEnoughDelimiters()
    {
        // given
        $delimiterer = new Delimiterer(new DelimiterParser(new FlagsValidator()));

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);

        // when
        $delimiterer->delimiter('s~i/e#++m%a!@*`_-;=');
    }
}
