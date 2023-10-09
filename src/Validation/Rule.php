<?php

namespace Lune\Validation;

use Lune\Validation\Rules\Email;
use Lune\Validation\Rules\LessThan;
use Lune\Validation\Rules\Number;
use Lune\Validation\Rules\Required;
use Lune\Validation\Rules\RequiredWhen;
use Lune\Validation\Rules\RequiredWith;
use Lune\Validation\Rules\ValidationRule;

class Rule
{
    public static function email(): ValidationRule
    {
        return new Email();
    }

    public static function required(): ValidationRule
    {
        return new Required();
    }

    public static function requiredWith(string $withField): ValidationRule
    {
        return new RequiredWith($withField);
    }

    public static function number(): ValidationRule
    {
        return new Number();
    }

    public static function lessThan(float $lessThan): ValidationRule
    {
        return new LessThan($lessThan);
    }

    public static function requiredWhen(string $comparedField, string $operator, string $value): ValidationRule
    {
        return new RequiredWhen($comparedField, $operator, $value);
    }
}
