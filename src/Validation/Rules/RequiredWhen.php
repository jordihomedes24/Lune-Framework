<?php

namespace Lune\Validation\Rules;

use Lune\Validation\Exceptions\RuleParseException;

class RequiredWhen implements ValidationRule
{
    private string $comparedField;

    private string $operator;

    private string $value;

    public function __construct(string $comparedField, string $operator, string $value)
    {
        $this->comparedField = $comparedField;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function message(): string
    {
        return "The field is required when $this->comparedField $this->operator $this->value";
    }

    public function isValid(string $field, array $data): bool
    {
        if (!array_key_exists($this->comparedField, $data)) {
            return false;
        }

        $isRequired = match ($this->operator) {
            "=" => $data[$this->comparedField] == $this->value,
            ">" => $data[$this->comparedField] > $this->value,
            ">=" => $data[$this->comparedField] >= $this->value,
            "<" => $data[$this->comparedField] < $this->value,
            "<=" => $data[$this->comparedField] <= $this->value,
            default => throw new RuleParseException("Unknown required_when operator: $this->operator")
        };

        return !$isRequired || (isset($data[$field]) && $data[$field] != "");
    }
}
