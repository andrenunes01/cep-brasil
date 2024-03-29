<?php

namespace CepGratis;

use CepGratis\Clients\CurlHttpClient;
use CepGratis\Contracts\HttpClientContract;
use CepGratis\Contracts\ProviderContract;
use CepGratis\Exceptions\CepGratisInvalidParameterException;
use CepGratis\Exceptions\CepGratisTimeoutException;
use CepGratis\Providers\CorreiosProvider;
use CepGratis\Providers\ViaCepProvider;

/**
 * Class to query CEP.
 */
class CepGratis
{
    /**
     * @var HttpClientContract
     */
    private $client;

    /**
     * @var ProviderContract[]
     */
    private $providers = [];

    /**
     * @var int
     */
    private $timeout = 5;

    /**
     * CepGratis constructor.
     */
    public function __construct()
    {
        $this->client = new CurlHttpClient();
    }

    /**
     * Search CEP on all providers.
     *
     * @param string $cep CEP
     *
     * @return Address
     */
    public static function search($cep)
    {

        $cepGratis = new self();
        $cepGratis->addProvider(new CorreiosProvider());
        $cepGratis->addProvider(new ViaCepProvider());

        $address = $cepGratis->resolve($cep);

        return $address;
    }

    /**
     * Performs provider CEP search.
     *
     * @param string $cep CEP
     *
     * @return Address
     */
    public function resolve($cep)
    {
        if ( strlen($cep) != 8 || !is_numeric($cep) ) {
            throw new CepGratisInvalidParameterException('CEP is invalid');
        }

        if (count($this->providers) == 0) {
            throw new CepGratisInvalidParameterException('No providers were informed');
        }

        /*
         * Execute
         */
        $time = time();
        $address = NULL;

        do {
            foreach ($this->providers as $provider) {
                if( is_null($address) ){
                    $address = $provider->getAddress($cep, $this->client);
                }
            }

            if ((time() - $time) >= $this->timeout) {
                throw new CepGratisTimeoutException("Maximum execution time of $this->timeout seconds exceeded in PHP");
            }
        } while (is_null($address));

        /*
         * Return
         */
        return $address;
    }

    /**
     * Set client http.
     *
     * @param HttpClientContract $client
     */
    public function setClient(HttpClientContract $client)
    {
        $this->client = $client;
    }

    /**
     * Set array providers.
     *
     * @param HttpClientContract $client
     */
    public function addProvider(ProviderContract $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * Set timeout.
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}
