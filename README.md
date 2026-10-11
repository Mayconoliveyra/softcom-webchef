# Softcom WebChef

Base local para o sistema de gestão de restaurante do treinamento de PHP e Laravel.

## Serviços locais

- Aplicação Laravel: `http://localhost:18080`
- phpMyAdmin: `http://localhost:18081`
- MySQL: acessível aos containers pelo host `db`

## Primeira execução

```powershell
docker compose up -d --build
docker compose exec app composer install --no-dev
docker compose exec app php artisan key:generate
docker compose restart app
```

As migrations padrão de autenticação ainda não foram executadas. Para habilitar cadastro e login, execute:

```powershell
docker compose exec app php artisan migrate
```

## Estrutura

- `routes/web.php`: rotas das telas.
- `app/Http/Controllers`: ações HTTP.
- `app`: models e regras da aplicação.
- `database/migrations`: estrutura do banco.
- `resources/views`: telas Blade com AdminLTE.
