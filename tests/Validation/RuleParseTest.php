<?php

namespace Lune\Tests\Validation;

use Lune\Validation\Exceptions\RuleParseException;
use Lune\Validation\Exceptions\UnknownRuleException;
use Lune\Validation\Rule;
use Lune\Validation\Rules\Email;
use Lune\Validation\Rules\LessThan;
use Lune\Validation\Rules\Number;
use Lune\Validation\Rules\Required;
use Lune\Validation\Rules\RequiredWhen;
use Lune\Validation\Rules\RequiredWith;
use PHPUnit\Framework\TestCase;

class RuleParseTest extends TestCase
{
    protected function setUp(): void
    {
        Rule::loadDefaultRules();
    }

    public static function basicRules(): array
    {
        return [
            [ Email::class, "email" ],
            [ Required::class, "required" ],
            [ Number::class, "number" ]
        ];
    }

    /**
     * @dataProvider basicRules
     */
    public function test_parse_basic_rules($class, $name)
    {
        $this->assertInstanceOf($class, Rule::from($name));
    }

    public function test_parsing_unknown_rules_throws_unknown_rule_exception()
    {
        $this->expectException(UnknownRuleException::class);
        Rule::from("unknown");
    }

    public static function rules_with_parameters()
    {
        return [
            [ new LessThan(10), "less_than:10" ],
            [ new RequiredWith("other"), "required_with:other" ],
            [ new RequiredWhen("other", "==", 10), "required_when:other,==,10" ]
        ];
    }

    /**
     * @dataProvider rules_with_parameters
     */
    public function test_parsing_rules_with_parameters($rule, $name)
    {
        $this->assertEquals($rule, Rule::from($name));
    }

    public static function rulesWithParametersWithError()
    {
        return [
            ["less_than"],
            ["less_than:"],
            ["required_with:"],
            ["required_when"],
            ["required_when:"],
            ["required_when:other"],
            ["required_when:other,"],
            ["required_when:other,="],
            ["required_when:other,=,"],
        ];
    }

    /**
     * @dataProvider rulesWithParametersWithError
     */
    public function test_parsing_rule_with_parameters_without_passing_correct_parameters_throws_rule_parse_exception($rule)
    {
        $this->expectException(RuleParseException::class);
        Rule::from($rule);
    }
}
