<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Match;

class MatchTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // when
        pattern('Hello (?<one>there)')
            ->match('Hello there, General Kenobi')
            ->first(function (Match $match) {

                // then
                $this->assertEquals(0, $match->offset());

                $this->assertEquals('there', $match->group('one'));
                $this->assertEquals('there', $match->group('one')->text());
                $this->assertEquals(6, $match->group('one')->offset());
                $this->assertTrue($match->group('one')->matches());

                $this->assertTrue($match->hasGroup('one'));
                $this->assertFalse($match->hasGroup('two'));
            });
    }

    /**
     * @test
     */
    public function shouldGroup_notMatch()
    {
        // when
        pattern('Hello (?<one>there)?')
            ->match('Hello XX, General Kenobi')
            ->first(function (Match $match) {

                // then
                $this->assertFalse($match->group('one')->matches());
            });
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);

        // when
        pattern('(?<one>hello)')
            ->match('hello')
            ->first(function (Match $match) {
                $match->group('two');
            });
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index can only be an integer or string, given: boolean (true)');

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $match->group(true);
            });
    }
}
