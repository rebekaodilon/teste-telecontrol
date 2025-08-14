# Teste Telecontrol

Aplicação de **Ordem de Serviço** desenvolvida em **PHP** com autenticação JWT, CRUDs de clientes, produtos e ordens de serviço, proteção de rotas, logs, validações de segurança e documentação Swagger.

## Tecnologias
- **PHP 8.3**
- **MySQL** (via Docker)
- **Bootstrap 5**
- **jQuery**
- **JWT**
- **Swagger**
- **Docker Compose**

---

## Estrutura do Projeto

```
.
├── docker-compose.yml    # Configuração do Docker
├── Dockerfile            # Imagem do PHP + Apache
├── .env                  # Variáveis de ambiente
├── scripts/              # Scripts de migração e seed
├── src/                  # Código fonte (Controllers, Services, etc.)
├── public/               # Frontend HTML + JS
└── docs/swagger.yaml     # Documentação da API
```

---

## Configuração e Execução

### Clonar o repositório
```bash
git clone https://github.com/rebekaodilon/teste-telecontrol.git
cd teste-telecontrol
```

### Criar o arquivo `.env`
Copie o `.env.example` para `.env`:
```bash
cp .env.example .env
```

Edite o `.env` conforme necessário:
```env
DB_HOST=mysql
DB_PORT=3306
DB_NAME=telecontrol
DB_USER=appuser
DB_PASS=apppassword
JWT_SECRET=seu_token_secreto
```

### Subir containers com Docker
```bash
docker compose up -d
```
Isso criará os serviços:
- **app** → PHP + Apache
- **mysql** → Banco de dados MySQL

### Instalar dependências
```bash
docker compose exec app composer install
```

### Criar tabelas e dados iniciais
```bash
docker compose exec app php scripts/migrate.php
docker compose exec app php scripts/seed.php
```

### Acessar a aplicação
- **Frontend:** [http://localhost:8080](http://localhost:8080)
- **Swagger:** [http://localhost:8080/docs](http://localhost:8080/docs)

---

## Login inicial
```
E-mail: admin@admin.com
Senha: 123456
```

---

## Rodar testes automatizados
```bash
docker compose exec app composer test
```

---

## Endpoints principais

- `POST /login` → Autenticação JWT
- `GET /clients` → Listar clientes
- `POST /clients` → Criar cliente
- `PUT /clients/{id}` → Atualizar cliente
- `DELETE /clients/{id}` → Deletar cliente
- `GET /products` → Listar produtos
- `POST /products` → Criar produto
- `PUT /products/{id}` → Atualizar produto
- `DELETE /products/{id}` → Deletar produto
- `GET /orders` → Listar ordens
- `POST /orders` → Criar ordem
- `PUT /orders/{id}` → Atualizar ordem
- `DELETE /orders/{id}` → Deletar ordem

---

## Licença
Este projeto foi desenvolvido para fins de teste técnico e não possui licença de uso comercial.
