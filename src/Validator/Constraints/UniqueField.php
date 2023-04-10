<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class UniqueField extends Constraint
{
    public const ALREADY_EXISTS = 'c1051cc5-d103-4f74-8988-acbcafc7fdc3';

    protected static $errorNames = [
        self::ALREADY_EXISTS => 'ALREADY_EXISTS',
    ];
    public $message = 'The value already exists.';

    public function __construct(array $options = null, string $message = null, array $groups = null, $payload = null)
    {
        parent::__construct($options ?? [], $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}