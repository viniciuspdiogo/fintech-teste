# Fintech

API para gerenciamento de contas digitais, com cadastro e autenticação de usuários, consulta de saldo, depósitos, saques e histórico de transações.

## Decisões técnicas

- **Laravel 13 e PHP 8.3** para a API e as regras de negócio.
- **Laravel Sanctum** para autenticação por token nas rotas protegidas.
- **Services** para concentrar operações de conta e transação, mantendo os controllers focados no fluxo HTTP.
- Saldos e valores são armazenados como inteiros. Depósitos e saques são executados em transações de banco de dados com bloqueio da conta, e cada operação registra o saldo resultante.
- **Vue 3, TypeScript, Vue Router, Pinia e Vite** compõem o frontend, mantido em `frontend/` e integrado à API Laravel.

## Como rodar localmente

Pré-requisitos: PHP 8.3+, Composer e Node.js 22.18+ (ou 24.12+) com npm.

Na raiz do projeto, instale as dependências do backend e prepare o ambiente. O projeto usa MySQL, então tenha o serviço instalado e em execução:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

No `.env` da raiz, configure a conexão MySQL com os dados do seu ambiente local. Crie o banco indicado em `DB_DATABASE` antes de executar as migrations:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Rode as migrations e os dados de demonstração:

```bash
php artisan migrate --seed
```

No arquivo `frontend/.env`, configure a URL da API local:

```dotenv
VITE_API_URL=http://127.0.0.1:8000/api
```

Inicie o backend e o frontend em terminais separados:

Terminal 1:

```bash
php artisan serve
```

Terminal 2, a partir da raiz:

```bash
cd frontend
npm install
npm run dev
```

O backend fica disponível em `http://127.0.0.1:8000` e o Vite informa o endereço do frontend ao iniciar. O seeder cria dados de demonstração.

## Testes e rotas da API

Execute os testes automatizados das rotas da API com:

```bash
php artisan test --testsuite=Api
```

Rotas disponíveis:

```terminaloutput
GET|HEAD   api/accounts/account ............................................................................. Api\AccountsController@findByUser
POST       api/accounts/deposit ................................................................................ Api\AccountsController@deposit
GET|HEAD   api/accounts/transactions .............................................................. Api\TransactionsController@listTransactions
POST       api/accounts/withdraw .............................................................................. Api\AccountsController@withdraw
POST       api/login ................................................................................................. Api\AuthController@login
POST       api/logout ............................................................................................... Api\AuthController@logout
POST       api/register ........................................................................................... Api\AuthController@register
GET|HEAD   api/user ......................................................................................................... routes/api.php:15
GET|HEAD   api/{fallbackPlaceholder} ........................................................................................ routes/api.php:30
```

## Deploy

O projeto pode ser publicado no Render como um serviço Docker usando o `Dockerfile` da raiz. Crie um banco PostgreSQL no Render e configure estas variáveis no serviço:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_KEY=<chave gerada com php artisan key:generate --show>
APP_URL=<URL pública do serviço no Render>
DB_CONNECTION=pgsql
DB_URL=<Internal Database URL do PostgreSQL no Render>
RUN_SEEDERS=false
```

O Render define a variável `PORT` automaticamente. A imagem compila o Vue em `frontend/` e serve a interface e a API pelo mesmo serviço. A cada inicialização, o container executa `php artisan migrate --force`. Para preencher um ambiente de demonstração, configure `RUN_SEEDERS=true`; isso cria o usuário `teste@exemplo.com` com a senha `123`, portanto não habilite essa opção em produção. O endereço de produção ainda não está configurado neste projeto. Adicione aqui o link após a publicação.
