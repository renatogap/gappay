<?php

namespace GapPay\Seguranca\Models\Form;

abstract class Form
{
    public static function create(array $data): static
    {
        $class = get_called_class();
        $instance = new $class;
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                //check if the property has a setter method
                $setter = 'set' . ucfirst($key);
                if (method_exists($instance, $setter)) {
                    $instance->$setter($value);
                } else {
                    $instance->$key = $value;
                }
            }
        }
        return $instance;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            return null;
        }
    }

    public function __toArray(): array
    {
        return get_object_vars($this);
    }
}