<?php

namespace Lune\Tests\Validation;

use Lune\Validation\Rule;
use PHPUnit\Framework\TestCase;

class ValidationRuleTest extends TestCase
{
    public static function emails()
    {
        return [
            ["test@test.com", true],
            ["antonio@mastermind.ac", true],
            ["test@testcom", false],
            ["test@test.", false],
            ["antonio@", false],
            ["antonio@.", false],
            ["antonio", false],
            ["@", false],
            ["", false],
            [null, false],
            [4, false],
        ];
    }

    /**
     * @dataProvider emails
     */
    public function test_email($email, $expected)
    {
        $data = [ 'email' => $email ];
        $rule = Rule::email();
        $this->assertEquals($expected, $rule->isValid('email', $data));
    }

    public static function requiredData()
    {
        return [
            ["", false],
            [null, false],
            [5, true],
            ["test", true],
        ];
    }

    /**
     * @dataProvider requiredData
     */
    public function test_required($value, $expected)
    {
        $data = [ 'test' => $value ];
        $rule = Rule::required();
        $this->assertEquals($expected, $rule->isValid('test', $data));
    }

    public function test_required_with()
    {
        $rule = Rule::requiredWith('test');
        $data = [ 'test' => 'a', 'other' => 'hello' ];
        $this->assertTrue($rule->isValid('other', $data));

        $data = [ 'test' => 'a', 'other' => '' ];
        $this->assertFalse($rule->isValid('other', $data));

        $data = [ 'test' => '', 'other' => '' ];
        $this->assertTrue($rule->isValid('other', $data));

        $data = [ 'other_test' => 'asdasd', 'other' => '' ];
        $this->assertTrue($rule->isValid('other', $data));
    }

    public static function lessThanData()
    {
        return [
            [5, 5, false],
            [5, 6, false],
            [5, 3, true],
            [5, null, false],
            [5, "", false],
            [5, "test", false],
        ];
    }

    /**
     * @dataProvider lessThanData
     */
    public function test_less_than($value, $check, $expected)
    {
        $rule = Rule::lessThan($value);
        $data = ["test" => $check];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public static function numbers()
    {
        return [
            [0, true],
            [1, true],
            [1.5, true],
            [-1, true],
            [-1.5, true],
            ["0", true],
            ["1", true],
            ["1.5", true],
            ["-1", true],
            ["-1.5", true],
            ["test", false],
            ["1test", false],
            ["-5test", false],
            ["", false],
            [null, false],
        ];
    }

    /**
     * @dataProvider numbers
     */
    public function test_number($n, $expected)
    {
        $rule = Rule::number();
        $data = ["test" => $n];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public static function requiredWhenData()
    {
        return [
            ["other", "=", "value", ["other" => "value"], "test", false],
            ["other", "=", "value", ["other" => "value", "test" => 1], "test", true],
            ["other", "=", "value", ["other" => "not value"], "test", true],
            ["other", ">", 5, ["other" => 1], "test", true],
            ["other", ">", 5, ["other" => 6], "test", false],
            ["other", ">", 5, ["other" => 6, "test" => 1], "test", true],
        ];
    }

    /**
     * @dataProvider requiredWhenData
     */
    public function test_required_when($other, $operator, $compareWith, $data, $field, $expected)
    {
        $rule = Rule::requiredWhen($other, $operator, $compareWith);
        $this->assertEquals($expected, $rule->isValid($field, $data));
    }
}
