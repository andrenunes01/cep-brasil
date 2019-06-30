<?php

namespace Casilhero\CepGratis;

class Address
{
    public $zipcode;

    public $street;

    public $neighborhood;

    public $city;

    public $state;

    public $provider;

    public static function create(array $data = [])
    {
        $address = new self();

        foreach (get_object_vars($address) as $name => $oldValue) {
            $address->{$name} = isset($data[$name]) ? $data[$name] : null;
        }

        return $address;
    }
}
