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
}
