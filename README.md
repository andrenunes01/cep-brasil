# cep-brasil
[![Latest Stable Version](https://poser.pugx.org/jansenfelipe/cep-gratis/v/stable.svg)](https://packagist.org/packages/casilhero/cep-brasil)
[![MIT license](https://poser.pugx.org/jansenfelipe/nfephp-serialize/license.svg)](http://opensource.org/licenses/MIT)

Projeto adaptado de https://github.com/jansenfelipe/cep-gratis com algumas melhorias.

Alterações: CorreiosProvider foi alterado para o novo fornecimento de dados dos correios.
ViaCepProvider foi alterado de forma que trate o retorno de erro do viaCep.


### Como utilizar

Adicione a library

```shell
$ composer require casilhero/cep-brasil
```

Adicione o autoload.php do composer no seu arquivo PHP.

```php
require_once 'vendor/autoload.php';
```

Agora basta chamar o método `CepGratis::search($cep)`

```php
use Casilhero\CepGratis\CepGratis;

$address = CepGratis::search('31030080');
```
### License

The MIT License (MIT)