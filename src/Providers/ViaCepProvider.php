<?php

namespace Casilhero\CepGratis\Providers;

use Casilhero\CepGratis\Address;
use Casilhero\CepGratis\Contracts\HttpClientContract;
use Casilhero\CepGratis\Contracts\ProviderContract;

class ViaCepProvider implements ProviderContract
{
    /**
     * @return Address|null
     */
    public function getAddress($cep, HttpClientContract $client)
    {
        $response = $client->get('https://viacep.com.br/ws/'.$cep.'/json/');
        $data = json_decode($response, true);

        if ( !is_null($data) && !isset($data['erro']) )
        {
            return Address::create([
                'zipcode'       => $cep,
                'street'        => $data['logradouro'],
                'neighborhood'  => $data['bairro'],
                'city'          => $data['localidade'],
                'state'         => $data['uf'],
                'provider'      => 'ViaCep',
            ]);
        }
    }
}
