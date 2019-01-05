<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\mapIfExists;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_first()
    {
        // given
        $subject = 'Replace one and two';
        $emptyMap = [];

        // when
        $result = pattern('(one|two)')->replace($subject)->first()->by()->mapIfExists($emptyMap);

        // then
        $this->assertEquals('Replace one and two', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_first_exists()
    {
        // given
        $subject = 'Replace one and two';
        $map = ['one' => 'first'];

        // when
        $result = pattern('(one|two)')->replace($subject)->first()->by()->mapIfExists($map);

        // then
        $this->assertEquals('Replace first and two', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_all()
    {
        // given
        $subject = 'Replace one!, two! and three!, and one!';
        $map = [
            'one!'   => 'first',
            'three!' => 'third'
        ];

        // when
        $result = pattern('(one|two|three)!')->replace($subject)->all()->by()->mapIfExists($map);

        // then
        $this->assertEquals('Replace first, two! and third, and first', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidKey()
    {
        // given
        $map = [2 => ''];

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map key. Expected string, but integer (2) given");

        // when
        pattern('(one|two)')->replace('')->first()->by()->mapIfExists($map);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidValue()
    {
        // given
        $map = ['' => true];

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map value. Expected string, but boolean (true) given");

        // when
        pattern('(one|two)')->replace('')->first()->by()->mapIfExists($map);
    }
}