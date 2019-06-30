<?php

namespace Casilhero\CepGratis\Contracts;

interface ProviderContract
{
    /**
     * @return Address
     */
    public function getAddress($cep, HttpClientContract $client);
}
