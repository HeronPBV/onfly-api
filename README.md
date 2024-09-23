# Onfly RESTful API - Gerenciamento de despesas

![GitHub repo size](https://img.shields.io/github/repo-size/HeronPBV/onfly-api?logo=github)
![Laravel](https://img.shields.io/badge/Laravel-11.23.5-c2363a?style=flat&logo=laravel)



## Sobre o projeto e seu desenvolvimento

API RESTful desenvolvida em Laravel para o gerenciamento eficiente de despesas. 💵
<br>O projeto é parte do teste técnico para o cargo de Desenvolvedor PHP no time da Onfly, sendo esta a sua única função: demonstrar conhecimento sólido em criação de API's RESTful com Laravel.
<br>Este projeto segue as melhores práticas de desenvolvimento, garantindo código limpo, escalável e de fácil manutenção.

### Tecnologias utilizadas

<table>
  <tr>
    <td>PHP</td>
    <td>Laravel</td>
    <td>MySQL</td>
  </tr>
  
  <tr>
    <td>8.2.18</td>
    <td>11.23.5</td>
    <td>8.3.0</td>
  </tr>
</table>

<br>Autorização e autenticação com o Laravel Sanctum 🔥
<br>Tradução de textos e mensagens para Pt_br por [lucascudo]([https://google.com](https://github.com/lucascudo/laravel-pt-BR-localization.git))

### Padrões de projeto
- Arquitetura MVC (Laravel)
- PSR4
- API Rest
- Clean Code

## Instruções para a execução do projeto

### 💻 Pré-requisitos

Antes de começar, verifique se você:

- Possui instalado em sua maquina o `Composer`, `Git`, `PHP` e `MySQL`, em versões recentes.
- Leu cuidadosamente todos os passos de instalação desta documentação.

### Para instalar e executar o projeto localmente

1º - Execute o seguinte comando no seu terminal:
~~~
git clone https://github.com/HeronPBV/onfly-api.git
~~~

<br>2º - Localize o arquivo onfly-api/.env.example e renomeio para .env, em seguida insira os seus dados de acesso ao MySQL nas linhas:
~~~
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_onfly
DB_USERNAME=root
DB_PASSWORD=
~~~
Há um arquivo database.sql com o comando para criar o banco de dados api_onfly. Execute-o ou crie o banco manualmente

<br>  3° - Execute os seguintes comandos no seu terminal para instalar e testar a aplicação:
~~~
composer install
~~~
~~~
php artisan test
~~~
Se todos os testes passarem sem problemas, você instalou corretamente e está pronto para prosseguir

<br> 4º - Execute os seguintes comandos no seu terminal para popular o banco de dados:
~~~
php artisan migrate
~~~
~~~
php artisan db:seed
~~~

<br> 5º - Execute os seguintes comandos no seu terminal para popular o banco de dados:
~~~
php artisan migrate
~~~
~~~
php artisan db:seed
~~~

<br> 6º - Em terminais separados, execute os seguintes comandos:
~~~
php artisan serve
~~~
~~~
php artisan queue:work --tries=3
~~~

⚠️ Atenção ⚠️ 
<br>Qualquer problema com o autoload pode ser resolvido com o seguinte comando:
~~~
composer dump
~~~

E pronto! Seu projeto já está configurado e pronto para testar. Confira os endpoints abaixo!
<br> Sugiro utilizar alguma ferramenta como o Postman ou o Insomnia para interagir com a aplicação.


## Lista dos endpoints e requisições

⚠️ Atenção ⚠️ 
<br>Por questões de simplificação, todos os endpoints abaixo terão como base o endereço onfly.api/
<br>Esse endereço pode variar de acordo com o seu ambiente local, se o retorno do comando `php artisan serve` for algo como `Server running on [http://localhost:8000/]` substitua "onfly.api/" por esse endereço ou o presente no retorno do seu comando.
<br> 
<br>Não é necessário enviar o header `Accept: application/json` em nenhuma requisição, pois a API possui um middleware que o adiciona automaticamente em todas as requisições, garantindo que todas as respostas serão em formato JSON

* GET: `onfly.api/`
<br>Home request da API
~~~
Retorno: 200 (OK)
{
    "API Onfly": "API RESTful, para gerenciamento de despesas e usuários.",
    "Instrução": "Acesse a documentação para descobrir os endpoints disponíveis",
    "Documentação": "https://github.com/HeronPBV/onfly-api"
}
~~~
<br>

### Usuário - Registro, Login, Logout

* POST: `onfly.api/api/registrar`
<br>Registro de novos usuários
~~~
Requisição:
{
    "nome": "João Silva",
    "email": "joaosilva@teste-exemplo.org",
    "senha": "123mudar"
}
~~~
~~~
Retorno: 201 (Created)
{
    "Sucesso": "Usuário registrado e logado com sucesso",
    "data": {
        "Token": "5|xfDyiS2x8IVQuJ8778DRPfq0BRAj4fiCzoieR24O612c49fb"
    }
}
~~~
A rota registra o novo usuário e já retorna um token de login para ser utilizado no Header de autenticação, por exemplo: `Authorization: Bearer 5|xfDyiS2x8IVQuJ8778DRPfq0BRAj4fiCzoieR24O612c49fb`
<br>É dessa forma que a aplicação sabe se o usuário está autenticado ou não.
<br>

* POST: `onfly.api/api/login`
<br>Login de usuários já registrados
~~~
Requisição:
{
    "email": "joaosilva@teste-exemplo.org",
    "senha": "123mudar"
}
~~~
~~~
Retorno: 200 (OK)
{
    "Sucesso": "Usuário logado com sucesso",
    "data": {
        "Token": "6|OqnKVYLgAvxQQdVgXzEYmlbDkuAwJ9Sdw0OFpfLnfc30327c"
    }
}
~~~
Da mesma forma, ao informar os dados válidos de um usuário já cadastrado, a aplicação retorna um token de login para ser usado da mesma forma. `Authorization: Bearer 6|OqnKVYLgAvxQQdVgXzEYmlbDkuAwJ9Sdw0OFpfLnfc30327c`

* GET ou POST: `onfly.api/api/logout`
<br>Logout com invalidação e deleção do token 
~~~
Header de requisição:
Authorization: Bearer 6|OqnKVYLgAvxQQdVgXzEYmlbDkuAwJ9Sdw0OFpfLnfc30327c
~~~
É necessário enviar o header de autorização com um token válido, ou a requisição retornará 401 (Unauthorized)
~~~
Retorno: 200 (OK)
{
    "Sucesso": "Usuário deslogado com sucesso"
}
~~~


### Despesas - Leitura, Criação, Atualização e Deleção

Todas as requisições que interagem com as despesas são protegidas por autenticação e policies, portanto é necessário sempre enviar o header:
~~~
Authorization: Bearer 6|OqnKVYLgAvxQQdVgXzEYmlbDkuAwJ9Sdw0OFpfLnfc30327c
~~~
Substituindo o token em questão pelo de um usuário logado, como ensinado nos endpoints anteriores. Por simplificação, não vou incluir na descrição dos endpoints abaixo, mas se o header não estiver incluso a aplicação retornará 401 (Unauthorized)


* GET: `onfly.api/api/despesas`
<br>Consulta de todas as despesas cadastradas
~~~
Retorno: 200 (OK)
{
    "Sucesso": "Lista de despesas",
    "data": [
            {
                "usuario": {
                    "nome": "Emílio Estêvão Rosa"
            },
                "descricao": "In ducimus porro totam reiciendis magni voluptates ex neque.",
                "data": "12/03/2018",
                "tempo_decorrido": "há 6 anos"
            },
            {
                "usuario": {
                    "nome": "Emílio Estêvão Rosa"
                },
                "descricao": "Et voluptatum nihil nam doloremque similique praesentium numquam.",
                "data": "15/04/2021",
                "tempo_decorrido": "há 3 anos"
            },
            {
                "usuario": {
                    "nome": "Heron Boares",
                    "email": "heronboares@gmail.com"
                },
                "descricao": "Eum velit et dolores enim ex qui incidunt.",
                "valor": "R$ 7.132,00",
                "data": "31/08/2022",
                "tempo_decorrido": "há 2 anos"
            },
            (...)
    ]
}
~~~
Repare que alguns dados como o email do usuário e o valor da despesa só são exibidos se aquela despesa específica pertencer ao usuário autenticado.


* GET: `onfly.api/api/despesas/{id}`
<br>Consulta de uma despesa específica (substitua {id} pelo id da despesa)
~~~
Retorno: 200 (OK)
{
    "Sucesso": "Detalhes da despesa",
    "data": {
        "usuario": {
            "nome": "Heron Boares",
            "email": "heronboares@gmail.com"
        },
        "descricao": "Eum velit et dolores enim ex qui incidunt.",
        "valor": "R$ 7.132,00",
        "data": "31/08/2022",
        "tempo_decorrido": "há 2 anos"
    }
}
~~~
Só é possível consultar os detalhes de uma despesa pertencente ao usuário autenticado. Tentativas de consultar despesas de outros usuários retornam 403 (Forbidden)


* POST: `onfly.api/api/despesas/`
<br>Cadastro de novas despesas
~~~
Requisição:
{
    "descricao": "aaaaaaaaaaaaaaa",
    "data": "20-09-2024",
    "valor": 100
}
~~~
~~~
Retorno: 200 (OK)
{
    "Sucesso": "Despesa criada com sucesso",
    "data": {
        "usuario": {
            "nome": "Heron Boares",
            "email": "heronboares@gmail.com"
        },
        "descricao": "aaaaaaaaaaaaaaa",
        "valor": "R$ 100,00",
        "data": "20/09/2024",
        "tempo_decorrido": "há 3 dias"
    }
}
~~~
Os dados precisam ser inseridos como estão descritos. Todos os campos estão devidamente validados: descrição com menos de 191 caracteres, data não pode ser futuro, etc.
<br> Nesse momento também é adicionado um job de notification/mail na fila de processamento assíncrono, para que seja enviado um email de notificação para o usuário cadastrado sem aumentar o tempo de resposta.


* PUT: `onfly.api/api/despesas/{id}`
<br>Atualização de uma despesa já existente (substitua {id} pelo id da despesa)
~~~
Requisição:
{
    "descricao": "aaaaaaaaaaaaaaa",
    "data": "20-09-2024",
    "valor": 1000
}
~~~
~~~
Retorno: 200 (OK)
{
    "Sucesso": "Despesa atualizada com sucesso",
    "data": {
        "usuario": {
            "nome": "Heron Boares",
            "email": "heronboares@gmail.com"
        },
        "descricao": "aaaaaaaaaaaaaaa",
        "valor": "R$ 1.000,00",
        "data": "20/09/2024",
        "tempo_decorrido": "há 3 dias"
    }
}
~~~
Os dados precisam ser inseridos como estão descritos, também é feita a válidação e não é possível atualizar uma despesa de outro usuário, o que retorna 403 (Forbidden).




* DELETE: `onfly.api/api/despesas/{id}`
<br>Deleção de uma despesa (substitua {id} pelo id da despesa)
~~~
Retorno: 200 (OK)
{
    "Sucesso": "Despesa deletada com sucesso!"
}
~~~
Não é possível deletar uma despesa de outro usuário, o que retorna 403 (Forbidden).

<br><br>
⚠️ Atenção ⚠️
<br>Qualquer requisição inválida ou que infringir alguma regra de negócio receberá uma resposta com status code 422 (Unprocessable Content) contendo o motivo da requisição não ser válida e instruções do que fazer.
<br>Exemplos:
~~~
{
    "message": "O campo descricao é obrigatório.",
    "errors": {
        "descricao": [
            "O campo descricao é obrigatório."
        ]
    }
}
~~~
~~~
{
    "message": "O campo data deve ser uma data anterior ou igual a today.",
    "errors": {
        "data": [
            "O campo data deve ser uma data anterior ou igual a today."
        ]
    }
}
~~~

~~~
{
    "message": "O campo descricao não pode ser superior a 191 caracteres.",
    "errors": {
        "descricao": [
            "O campo descricao não pode ser superior a 191 caracteres."
        ]
    }
}
~~~

Entre outras. Há diversas validações para garantir que só sejam processadas requisições que façam sentido.
