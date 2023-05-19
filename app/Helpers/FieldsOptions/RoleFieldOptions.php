<?php

namespace App\Helpers\FieldsOptions;

/**
 * Opciones disponibles para el campo Estado
 */
enum RoleFieldOptions: int implements BaseFunctionsFieldsOptions
{
    case ADMIN = 1;
    case CLIENT = 2;

    public function label(): string
    {
        return match ($this) {
            static::ADMIN => __('Administrador'),
            static::CLIENT => __('Cliente')
        };
    }

    public static function toArray(): array
    {
        $options = [];
        foreach (static::cases() as $case)
            $options[] = array('value' => $case->value, 'label' => $case->label());

        return $options;
    }

    public function jsonSerialize(): mixed
    {
        return array('value' => $this->value, 'label' => $this->label());
    }
}
