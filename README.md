ACL
=============

Biblioteca para manipular acesso de usuário a sistemas

USO
===

## Instalação

`composer require toneladas/acl`

## Configurando

Você pode usar a biblioteca usando uma conexão PDO "pura"\* ou usando uma entidade da Doctrine.

A biblioteca utiliza as funções [password_hash](http://php.net/manual/en/function.password-hash.php) e [password_verify](http://php.net/manual/en/function.password-verify.php), então ao salvar os dados do usuário no banco, utilize essas funções.

1. *Usando PDO "pura"*

Configure a conexão usando `setWithDatabase`:

```php
<?php

$conn = new \PDO('sqlite::memory:');

$acl = new \Toneladas\Acl();
$acl->setWithDatabase($conn); // Objeto da PDO usado para conexão no banco de dados
$acl->setTable('usuarios'); // Nome da tabela usada no banco para guardar os usuários
$acl->setFieldUser('email'); // Nome do campo na tabela usado como o nome do usuário
$acl->setFieldPassword('senha'); // Nome do campo na tabela usado como a senha do usuário

```

## Verificando

Para verificar se o usuário e senha estão corretos, passe os dados vindo do formulários como parametros do método `verify`:

```php
<?php

try {
  $acl->verify($usuario, $senha);

  // Deu tudo certo, seguimos em frente
} catch (\Exception $exp) {
  // Alguma coisa deu errada
}
```

Ele pode retornar duas Exceptions:

- `\Toneladas\Exceptions\UserWrongException`: Usuário passado não foi encontrado no banco
- `\Toneladas\Exceptions\PasswordWrongException`: A senha informada está errada

Você pode ainda tratar as exceptions diferente para cada situação:

```php
<?php

try {
  $acl->verify($usuario, $senha);

  // Deu tudo certo, seguimos em frente
} catch (\Toneladas\Exceptions\UserWrongException $exp) {
  // O usuário não foi encontrado, então faço alguma coisa
} catch (\Toneladas\Exceptions\PasswordWrongException $exp) {
  // A senha está incorreta, então faço outra coisa
}
```

__Lembrando que é boa prática não informar ao usuário qual dos dois deu errado, mas só informar que ocorreu um erro__


\* Eu sei que o Doctrine também usa a PDO para conexão com o banco

Licença
=======

### Licenciado sobre a licença MIT

Veja o arquivo [LICENSE](/LICENSE) para mais detalhes.
