<?php

namespace KoineTests;

use Koine\String;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class StringTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Koine\String::__toString()
     */
    public function testItCanBeConvertedToString()
    {
        $string = new String('hello');
        $this->assertEquals('hello world', $string . ' world');
    }

    /**
     * @covers Koine\String::append()
     */
    public function testItCanAppendString()
    {
        $string = new String;
        $string->append('abc')->append(new String('de'));
        $this->assertEquals('abcde', (string) $string);
    }

    /**
     * Data Provider
     */
    public function caseProvider()
    {
        return array(
            array('abc', 'ABC'),
            array('saúdações', 'SAÚDAÇÕES'),
        );
    }

    /**
     * @dataProvider caseProvider
     * @covers Koine\String::toUpperCase()
     */
    public function testToUppercase($lower, $upper)
    {
        $string = new String($lower);
        $this->assertEquals($upper, $string->toUpperCase());
        $this->assertInstanceOf('Koine\String', $string->toUpperCase());
    }

    /**
     * @dataProvider caseProvider
     * @covers Koine\String::toLowerCase()
     */
    public function testToLowerCase($lower, $upper)
    {
        $string = new String($upper);
        $this->assertEquals($lower, $string->toLowerCase());
        $this->assertInstanceOf('Koine\String', $string->toLowerCase());
    }

    /**
     * Data Provider
     */
    public function parameterizedProvider()
    {
        return array(
            array('Foo Bar', 'foo-bar', '-'),
            array('Foo Bar', 'foo_bar', '_'),
            array('M!@#$%)ab C', 'mab-c', '-'),
        );
    }

    /**
     * @dataProvider parameterizedProvider
     * @covers Koine\String::parameterize()
     */
    public function testParameterize($normal, $parameterized, $separator)
    {
        $string = new String($normal);
        $this->assertEquals($parameterized, $string->parameterize($separator));
        $this->assertInstanceOf('Koine\String', $string->toLowerCase());
    }

    /**
     * Data Provider
     */
    public function providerForGsub()
    {
        return array(
            array('abcdabc', 'a', 'A', 'AbcdAbc'),
            array('abcdabc', '/[ac]/', 'A', 'AbAdAbA'),
        );
    }

    /**
     * @dataProvider providerForGsub
     * @covers Koine\String::gsub()
     */
    public function testGsub($string, $find, $replacement, $expected)
    {
        $string = new String($string);
        $result = $string->gsub($find, $replacement);
        $this->assertEquals($expected, $result);
        $this->assertInstanceOf('Koine\String', $result);
    }

    public function testSplit()
    {
        $string   = new String('a, b, c');
        $expected = array('a', 'b', 'c');
        $split    = $string->split(', ');
        $this->assertEquals($expected, $split->toArray());
        $this->assertInstanceOf('Koine\String', $split->first());
    }

    public function countProvider()
    {
        return array(
            array('abc', 3),
            array('ÂçÇÉ', 4),
        );
    }

    /**
     * @dataProvider countProvider
     */
    public function testCount($string, $count)
    {
        $string = new String($string);
        $this->assertEquals($count, $string->count());
    }

    public function atProvider()
    {
        return array(
            array('abcdef', 0, null, 'abcdef'),
            array('abcdef', 5, null, 'f'),
            array('abcdef', 1, 3,    'bcd'),
            array('não avião',  1, null, 'ão avião'),
            array('não avião',  1, 7, 'ão aviã'),
            array('não avião',  4, 5, 'avião'),
        );
    }

    /**
     * @dataProvider atProvider
     */
    public function testAt($value, $start, $end, $expected)
    {
        $object = new String($value);
        $result = $object->at($start, $end);

        $this->assertInstanceOf('Koine\String', $result);
        $this->assertEquals($expected, (string) $result);
    }

    public function testTrim()
    {
        $object = new String('  abc ');
        $this->assertEquals('abc', $object->trim()->toString());
    }
}
