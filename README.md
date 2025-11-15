# 🚗 API SENATRAN - Validação de CNH

API REST para validação e cadastro de CNH (Carteira Nacional de Habilitação) desenvolvida em Laravel.

## 📋 Índice

- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Executando o Projeto](#executando-o-projeto)
- [Documentação da API](#documentação-da-api)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)

## 🔧 Requisitos

- PHP >= 8.1
- Composer
- Node.js >= 18.0
- MySQL/MariaDB
- Git

## 📦 Instalação

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd senatran-api
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Configure o arquivo de ambiente

```bash
cp .env.example .env
```

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

## ⚙️ Configuração

### 1. Configure o banco de dados

Edite o arquivo `.env` e configure suas credenciais do banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=senatran
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 2. Execute as migrations

```bash
php artisan migrate
```

### 3. Popule o banco com dados de teste

```bash
php artisan db:seed
```

Isso criará 6 registros de CNH para testes.

### 4. Gere a documentação Swagger

```bash
php artisan l5-swagger:generate
```

## 🚀 Executando o Projeto

```bash
php artisan serve
```

A API estará disponível em: `http://localhost:8000`

## 📚 Documentação da API

A documentação interativa (Swagger/OpenAPI) está disponível em:

```
http://localhost:8000/api/documentation
```

## 🛠️ Tecnologias Utilizadas

- **Laravel 11** - Framework PHP
- **MySQL/MariaDB** - Banco de dados
- **Swagger/OpenAPI** - Documentação da API
- **L5-Swagger** - Geração de documentação
- **Composer** - Gerenciador de dependências PHP
