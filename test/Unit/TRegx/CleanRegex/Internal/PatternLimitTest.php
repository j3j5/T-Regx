<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Remove\RemoveLimit;
use TRegx\CleanRegex\Replace\ReplaceLimitImpl;
use TRegx\CleanRegex\Replace\ReplacePattern;

/*
 * This test checks that implementations of PatternLimit have all
 * interface methods, but also apply separate return types. This
 * is possible because PatternLimit doesn't apply any return types.
 */

class PatternLimitTest extends TestCase
{
    /**
     * @test
     * @dataProvider implementations
     * @param PatternLimit $limit
     */
    public function shouldCallInterfaceMethods(PatternLimit $limit)
    {
        // when
        $limit->all();
        $limit->first();
        $limit->only(2);

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider implementations
     * @param PatternLimit $limit
     */
    public function shouldThrowOnNegativeLimit(PatternLimit $limit)
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit -2');

        // when
        $limit->only(-2);

        // then
        $this->assertTrue(true);
    }

    public function implementations()
    {
        return [
            [
                new ReplaceLimitImpl(function (int $limit) {
                    return new ReplacePattern(new Pattern(''), '', $limit);
                })
            ],
            [
                new RemoveLimit(function () {
                    return '';
                })
            ],
        ];
    }
}
