# 🔐 Secure Auth API

API REST de autenticação segura desenvolvida com Laravel, implementando OAuth2, práticas avançadas de segurança e arquitetura limpa seguindo princípios SOLID.

## 📋 Sobre o Projeto

Este projeto é uma API de autenticação robusta e segura, projetada para aplicações que exigem alto nível de segurança, como sistemas financeiros. A API implementa autenticação OAuth2 com Passport, proteção contra ataques comuns, rate limiting, e segue as melhores práticas de desenvolvimento com Clean Code e SOLID.

### 🎯 Objetivos

- Implementar autenticação segura com OAuth2 e JWT
- Aplicar práticas avançadas de segurança (OWASP Top 10)
- Seguir princípios SOLID e Clean Architecture
- Criar uma base sólida para aplicações que exigem alta segurança
- Demonstrar boas práticas de desenvolvimento Laravel

## 🚀 Tecnologias

### Core
- **[Laravel 12.x](https://laravel.com)** - Framework PHP
- **[PHP 8.2+](https://www.php.net/)** - Linguagem de programação
- **[PostgreSQL 16](https://www.postgresql.org/)** - Banco de dados relacional
- **[Redis](https://redis.io/)** - Cache e sessões

### Autenticação & Segurança
- **[Laravel Passport](https://laravel.com/docs/passport)** - OAuth2 Server
- **JWT (JSON Web Tokens)** - Tokens de autenticação
- **Rate Limiting** - Proteção contra força bruta
- **CORS** - Controle de acesso cross-origin
- **Encryption** - Criptografia de dados sensíveis

### Infraestrutura
- **[Docker](https://www.docker.com/)** - Containerização
- **[Docker Compose](https://docs.docker.com/compose/)** - Orquestração de containers
- **[Nginx](https://www.nginx.com/)** - Servidor web

### Desenvolvimento
- **[Composer](https://getcomposer.org/)** - Gerenciador de dependências PHP
- **[Pest](https://pestphp.com/)** - Framework de testes
- **[PHPUnit](https://phpunit.de/)** - Testes unitários

## 🏗️ Arquitetura

O projeto segue os princípios de **Clean Architecture** e **SOLID**:

```
app/
├── Http/
│   ├── Controllers/     # Controladores (camada de apresentação)
│   ├── Requests/        # Validação de requisições
│   └── Resources/       # Transformação de respostas
├── Models/              # Modelos Eloquent
├── Services/            # Lógica de negócio
├── Repositories/        # Camada de acesso a dados
└── Providers/           # Service Providers
```

### Recursos Implementados

#### Autenticação e Autorização
- ✅ Registro de usuários com validação
- ✅ Login com OAuth2 (Laravel Passport)
- ✅ Refresh tokens (renovação automática)
- ✅ Logout seguro (revogação de tokens)
- ✅ Endpoint /me (dados do usuário autenticado)

#### Segurança
- ✅ HTTPS obrigatório em produção
- ✅ Security Headers (HSTS, CSP, X-Frame-Options, etc.)
- ✅ Rate Limiting por IP (login, register, refresh)
- ✅ Validação de senha forte (complexidade + senhas comprometidas)
- ✅ Audit Logging (login, logout, registro)
- ✅ CORS configurado
- ✅ Criptografia de senhas com bcrypt
- ✅ Prepared Statements (Eloquent ORM)

#### Infraestrutura
- ✅ Docker (PHP 8.3, PostgreSQL, Nginx, Redis)
- ✅ Migrations para versionamento do banco
- ✅ Testes automatizados (PHPUnit)
- ✅ Documentação da API com Swagger/OpenAPI

### Recursos Planejados

- 🔄 Autenticação de dois fatores (2FA)
- 🔄 Recuperação de senha via email
- 🔄 Verificação de e-mail
- 🔄 Permissões e roles (RBAC)
- 🔄 API de gerenciamento de usuários (CRUD completo)
- 🔄 Notificações em tempo real
- 🔄 CI/CD com GitHub Actions

## 📦 Instalação e Configuração

### Pré-requisitos

- Docker e Docker Compose instalados
- Git

### Passo a Passo

1. **Clone o repositório**
```bash
git clone https://github.com/victorufg/secure-auth-api.git
cd secure-auth-api
```

2. **Configure as variáveis de ambiente**
```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas configurações:
```env
APP_NAME="Secure Auth API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# CORS - Origens permitidas (separadas por vírgula)
# Deixe vazio para permitir todas (*) em desenvolvimento
# Exemplo: CORS_ALLOWED_ORIGINS=http://localhost:3000,https://app.example.com
CORS_ALLOWED_ORIGINS=

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=auth_api
DB_USERNAME=postgres
DB_PASSWORD=secret

CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379
```

3. **Inicie os containers Docker**
```bash
docker-compose up -d
```

4. **Instale as dependências**
```bash
docker-compose exec app composer install
```

5. **Gere a chave da aplicação**
```bash
docker-compose exec app php artisan key:generate
```

6. **Execute as migrations**
```bash
docker-compose exec app php artisan migrate
```

7. **Instale o Passport**
```bash
docker-compose exec app php artisan passport:install
```

A API estará disponível em `http://localhost:8000`

## 🧪 Testes

### Executar todos os testes
```bash
docker-compose exec app php artisan test
```

### Executar testes específicos
```bash
# Testes de feature
docker-compose exec app php artisan test --testsuite=Feature

# Testes unitários
docker-compose exec app php artisan test --testsuite=Unit

# Teste específico
docker-compose exec app php artisan test --filter=ExampleTest
```

### Cobertura de testes
```bash
docker-compose exec app php artisan test --coverage
```

## 📚 Documentação da API

### Swagger UI (Documentação Interativa)

Acesse a documentação interativa da API em:

```
http://localhost/api/documentation
```

**Recursos:**
- 📖 Documentação completa de todos os endpoints
- 🧪 Teste os endpoints diretamente no navegador
- 📋 Exemplos de requisições e respostas
- 🔐 Suporte para autenticação Bearer Token

### Endpoints Principais


#### Registro
```http
POST /api/register
Content-Type: application/json

{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "SenhaForte123!",
  "password_confirmation": "SenhaForte123!"
}
```

**Requisitos de Senha:**
- **Desenvolvimento**: Mínimo de 8 caracteres
- **Produção**: 
  - Mínimo de 12 caracteres
  - Pelo menos uma letra maiúscula (A-Z)
  - Pelo menos uma letra minúscula (a-z)
  - Pelo menos um número (0-9)
  - Pelo menos um caractere especial (@$!%*#?&)
  - Não pode ser uma senha comprometida em vazamentos de dados


#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "joao@example.com",
  "password": "senha123"
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

#### Refresh Token
```http
POST /api/refresh
Authorization: Bearer {token}
```

## 🔒 Segurança

Este projeto implementa diversas camadas de segurança:

- **Autenticação OAuth2** com tokens de acesso e refresh
- **HTTPS obrigatório** em produção (URLs forçadas para HTTPS)
- **Security Headers** (HSTS, CSP, X-Frame-Options, X-Content-Type-Options, etc.)
- **Audit Logging** de ações sensíveis (login, logout, registro)
- **Rate Limiting** para prevenir ataques de força bruta
- **Validação rigorosa** de todas as entradas com Form Requests
- **Validação de senha forte** com verificação de senhas comprometidas
- **Tokens de curta duração** (15 minutos) para reduzir janela de exploração
- **CORS** configurado para controlar acesso cross-origin
- **Criptografia** de senhas com bcrypt
- **Prepared Statements** (Eloquent) para prevenir SQL Injection

## 🛠️ Desenvolvimento

### Comandos Úteis

```bash
# Acessar o container da aplicação
docker-compose exec app bash

# Ver logs
docker-compose logs -f app

# Parar os containers
docker-compose down

# Rebuild dos containers
docker-compose up -d --build

# Limpar cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
```

## 📝 Roadmap

- [x] Setup inicial do projeto
- [x] Configuração Docker
- [x] Autenticação básica com Passport
- [x] Refresh Tokens
- [x] Rate Limiting
- [x] Validação de senha forte
- [x] Security Headers (HSTS, CSP, etc.)
- [x] CORS configurado
- [x] Auditoria de acessos (Audit Logging)
- [x] Documentação Swagger/OpenAPI
- [x] CI/CD com GitHub Actions
- [ ] Implementar 2FA
- [ ] Sistema de permissões (RBAC)
- [ ] Recuperação de senha via email
- [ ] Verificação de email
- [ ] Monitoramento e logs avançados

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor:

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Victor Hugo**
- GitHub: [@victorufg](https://github.com/victorufg)

---

⭐ Se este projeto foi útil para você, considere dar uma estrela!
