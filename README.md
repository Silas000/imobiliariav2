# Sistema Imobiliária v2.0

Sistema de gerenciamento de imóveis para uma imobiliária, desenvolvido em PHP com PDO/MySQL. Permite o cadastro de casas/com apartamentos com fotos, gerenciamento de usuários administradores e exibição pública dos imóveis à venda.

## Requisitos

- PHP 7.4 ou superior (testado em PHP 8.x)
- MySQL 5.7+ ou MariaDB 10.4+
- Extensão PDO MySQL habilitada
- Extensão php_fileinfo habilitada (recomendado)

## Instalação

### Passo 1 - Criar o Banco de Dados

**Opção A: Via script PHP (recomendado para iniciantes)**

1. Acesse `http://localhost/imobiliaria2/install.php` no navegador
2. O script criará automaticamente o banco `imobiliaria2` e todas as tabelas
3. **Remova o arquivo `install.php` após a instalação** por segurança

**Opção B: Via phpMyAdmin ou cliente MySQL**

```bash
mysql -u root -p < database.sql
```

### Passo 2 - Configurar a Conexão

Edite `db.php` e ajuste as credenciais conforme seu ambiente:

```php
$dsn = 'mysql:host=localhost;dbname=imobiliaria2;charset=utf8';
$username = 'root';
$password = '';
```

### Passo 3 - Criar o Primeiro Usuário Administrador

Como não existe nenhum usuário ainda, acesse diretamente:

`http://localhost/imobiliaria2/cadastrar_usuario.php`

> **Nota:** A tela de login (`login.php`) também exibe um link "Criar primeiro administrador" quando o banco não possui usuários.

Preencha o formulário para criar seu primeiro administrador. Após o cadastro, retorne ao `login.php` para entrar no sistema.

### Passo 4 - Acessar o Sistema

- **Painel administrativo:** `http://localhost/imobiliaria2/login.php`
- **Página pública:** `http://localhost/imobiliaria2/index.php`

## Estrutura do Projeto

```
imobiliaria2/
├── css/
│   └── style.css          # Estilos do sistema (menu + footer)
├── uploads/                 # Imagens enviadas (protegido contra execução PHP)
│   └── .htaccess
├── database.sql             # Schema do banco em SQL puro
├── install.php              # Script de instalação automatizada
├── db.php                   # Conexão com o banco (PDO)
├── csrf.php                 # Helper de tokens CSRF
├── login.php                # Tela de login
├── logout.php               # Logout e destruição de sessão
├── dashboard.php            # Painel principal
├── index.php                # Página pública (lista de imóveis)
├── detalhes.php             # Detalhes de um imóvel (público)
├── menu.php                 # Menu lateral do admin
├── cadastrar_casa.php       # Formulário de cadastro de imóveis
├── cadastrar_usuario.php    # Formulário de cadastro de usuários
├── editar_casa.php          # Edição de imóveis
├── editar_usuario.php       # Edição de usuários
├── excluir_casa.php         # Exclusão de imóveis
├── excluir_usuario.php      # Exclusão de usuários
├── listar_casas.php         # Lista de imóveis no admin
├── listar_usuarios.php      # Lista de usuários no admin
├── .htaccess                # URLs amigáveis + proteção de arquivos
└── AGENTS.md                # Instruções para agentes de código
```

## Funcionalidades

### Administração (`login.php`)

| Funcionalidade | Descrição |
|---|---|
| Cadastro de usuários | Cria novos administradores com senha hash (bcrypt) |
| Edição de usuários | Atualiza usuário e/ou senha (senha opcional) |
| Exclusão de usuários | Remove com confirmação via modal |
| Cadastro de casas | Imóvel com nome, descrição, endereço, WhatsApp e fotos |
| Upload múltiplo de fotos | Validação de tipo, tamanho e conteúdo real (getimagesize) |
| Edição de casas | Atualiza todos os campos do imóvel |
| Listagem de casas | Tabela com ações de editar/excluir |
| Listagem de usuários | Tabela com ações de editar/excluir |

### Público (`index.php`, `detalhes.php`)

- Exibição de todos os imóveis em cards com carrossel de imagens
- Página de detalhes com carrossel, descrição, endereço e link WhatsApp

## Medidas de Segurança

| Controle | Implementação |
|---|---|
| **CSRF** | Tokens em todos os forms via `csrf.php` (`hash_equals`) |
| **Password hashing** | `password_hash()` / `password_verify()` (bcrypt) |
| **XSS** | `htmlspecialchars()` em todas as saídas HTML |
| **Session fixation** | `session_regenerate_id(true)` após login |
| **SQL Injection** | PDO com prepared statements + `intval()` em IDs |
| **Upload security** | Validação: getimagesize, mime, extensão, double-extension, tamanho (5MB), nome único |
| **PHP em uploads** | `.htaccess` bloqueia execução de PHP na pasta uploads |
| **Arquivos sensíveis** | `.htaccess` protege `db.php` e `csrf.php` |
| **Error handling** | Erros logados via `error_log()`, usuário vê mensagem genérica |

## Como Usar

### Gerenciar imóveis

1. Faça login no painel administrativo
2. No menu, clique em **Cadastrar Casas** para adicionar um novo imóvel
3. Selecione múltiplas fotos (JPG, JPEG, PNG, GIF - até 5MB cada)
4. Use **Listar Casas** para ver, editar ou excluir imóveis cadastrados

### Gerenciar usuários

1. Clique em **Cadastrar Usuários** no menu
2. Use **Listar Usuários** para gerenciar administradores existentes

## Desenvolvido por

**Silas Rosário**
