<?php

namespace Casilhero\CepGratis\Providers;

use Casilhero\CepGratis\Address;
use Casilhero\CepGratis\Contracts\HttpClientContract;
use Casilhero\CepGratis\Contracts\ProviderContract;
use Symfony\Component\DomCrawler\Crawler;

class CorreiosProvider implements ProviderContract
{
    /**
     * @return Address
     */
    public function getAddress($cep, HttpClientContract $client)
    {
      $response = $client->post('http://www.buscacep.correios.com.br/sistemas/buscacep/detalhaCEP.cfm', [
        'CEP' => $cep,
      ]);

      if (!is_null($response)) {
        $crawler = new Crawler($response);

        $message = $crawler->filter('div.ctrlcontent p')->html();

        if ( !preg_match('/CEP NAO ENCONTRADO/', $message) && $crawler->filter('table.tmptabela')->count() > 0) {
          $tr = $crawler->filter('table.tmptabela');

          $params['zipcode'] = $cep;
          $params['street'] = '';
          $params['neighborhood'] = '';
          $params['city'] = '';
          $params['state'] = '';
          $params['provider'] = 'correios';

          for ($i=1; $i <= 4; $i++) {
            if( $tr->filter('tr:nth-child('.$i.') th:nth-child(1)')->count() > 0 )
            {
              $informacoes[$i] = $tr->filter('tr:nth-child('.$i.') th:nth-child(1)')->html();
              if( preg_match('/(L|l)(ogradouro)/', $informacoes[$i]) )
              {
                $params['street'] = $tr->filter('tr:nth-child('.$i.') td:nth-child(2)')->html();
                $aux = explode(' - ', $params['street']);
                $params['street'] = (count($aux) == 2) ? $aux[0] : $params['street'];
              }
              else if ( preg_match('/(B|b)(airro)/', $informacoes[$i]) )
              {
                $params['neighborhood'] = $tr->filter('tr:nth-child('.$i.') td:nth-child(2)')->html();
              }
              else if ( preg_match('/(L|l)(ocalidade)/', $informacoes[$i]) )
              {
               $aux = explode('/', $tr->filter('tr:nth-child('.$i.') td:nth-child(2)')->html() );
               $params['city'] = $aux[0];
               $params['state'] = $aux[1];
             }
           } else break;
         }

         return Address::create(array_map(function ($item) { return urldecode(str_replace('%C2%A0', '', urlencode($item))); }, $params));
       }
     }
   }
 }
