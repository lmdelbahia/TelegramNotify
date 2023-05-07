<?php

namespace App\Helpers\FieldsOptions;

use JsonSerializable;

interface BaseFunctionsFieldsOptions extends JsonSerializable
{
    public function label(): string;
    public static function toArray(): array;
}
