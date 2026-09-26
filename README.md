# Sistema Imobiliária v2.0

Sistema de gerenciamento de imóveis para uma imobiliária, desenvolvido em PHP com PDO/MySQL. Permite o cadastro de casas/com apartamentos com fotos, gerenciamento de usuários administradores e exibição pública dos imóveis à venda.

## Requisitos

- PHP 7.4 ou superior (testado em PHP 8.x)
- MySQL 5.7+ ou MariaDB 10.4+
- Extensão PDO MySQL habilitada

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

Acesse `http://localhost/imobiliaria2/cadastrar_usuario.php`. Como não há usuários cadastrados, o acesso é liberado automaticamente para criar o primeiro administrador.

### Passo 4 - Acessar o Sistema

- **Painel administrativo:** `http://localhost/imobiliaria2/login.php`
- **Página pública:** `http://localhost/imobiliaria2/index.php`

## Estrutura do Projeto

```
imobiliaria2/
├── css/
│   └── style.css          # Estilos completos (sidebar, footer, cards, login)
├── uploads/                 # Imagens enviadas (protegido contra PHP)
│   ├── .htaccess          # Bloqueia execução de PHP
│   └── placeholder.svg    # Imagem fallback para imóveis sem foto
├── head.php                 # Include: <head> com CSS e meta tags
├── footer.php               # Include: footer + scripts JS
├── menu.php                 # Include: sidebar de navegação admin
├── database.sql             # Schema do banco em SQL puro
├── install.php              # Script de instalação automatizada
├── db.php                   # Conexão com o banco (PDO, error_log)
├── csrf.php                 # Helper de tokens CSRF
├── login.php                # Tela de login (redesenhada)
├── logout.php               # Logout e destruição de sessão
├── dashboard.php            # Painel principal (cards de contagem)
├── index.php                # Página pública (lista de imóveis)
├── detalhes.php             # Detalhes de um imóvel (público)
├── cadastrar_casa.php       # Cadastro de imóveis (upload + máscara WhatsApp)
├── cadastrar_usuario.php    # Cadastro de usuários (primeiro acesso permitido)
├── editar_casa.php          # Edição de imóveis
├── editar_usuario.php       # Edição de usuários
├── excluir_casa.php         # Exclusão via POST + CSRF
├── excluir_usuario.php      # Exclusão via POST + CSRF
├── listar_casas.php         # Lista de imóveis no admin
├── listar_usuarios.php      # Lista de usuários no admin
├── .htaccess                # URLs amigáveis + proteção de arquivos
└── README.md
```

## Funcionalidades

### Administração

| Funcionalidade | Descrição |
|---|---|
| **Login** | Tela de login com design moderno (gradiente roxo → azul) |
| Cadastro de usuários | Cria novos administradores com `password_hash()` (bcrypt) |
| Edição de usuários | Atualiza usuário e/ou senha (senha opcional) |
| Exclusão de usuários | Remove com confirmação via modal |
| Cadastro de casas | Imóvel com nome, descrição, endereço, WhatsApp e fotos múltiplas |
| Upload múltiplo de fotos | Validação: getimagesize, extensão, double-extension, tamanho (5MB), nome único |
| Máscara de WhatsApp | Formatação automática `(xx) xxxxx-xxxx` em tempo real |
| Edição de casas | Atualiza todos os campos incluindo preço |
| Listagem de casas | Tabela com ações de editar/excluir (modais de confirmação) |
| Listagem de usuários | Tabela com ações de editar/excluir (modais de confirmação) |
| Dashboard | Cards dinâmicos mostrando contagem de casas e usuários |

### Público

- Exibição de imóveis em cards com carrossel de imagens
- Fallback para `placeholder.svg` quando imóvel não tem fotos
- Botão de contato via WhatsApp com link direto
- Página de detalhes com carrossel, descrição, endereço, preço e contato

## Medidas de Segurança

| Controle | Implementação |
|---|---|
| **CSRF** | Tokens em todos os forms via `csrf.php` (`hash_equals`) |
| **Password hashing** | `password_hash()` / `password_verify()` (PASSWORD_DEFAULT - bcrypt) |
| **XSS** | `htmlspecialchars()` em todas as saídas HTML |
| **Session fixation** | `session_regenerate_id(true)` após login |
| **SQL Injection** | PDO prepared statements + `intval()` em IDs |
| **Upload security** | getimagesize, whitelist de extensões, double-extension check, tamanho (5MB), nome único (`uniqid()`) |
| **PHP em uploads** | `.htaccess` bloqueia execução de PHP na pasta uploads |
| **Arquivos sensíveis** | `.htaccess` protege `db.php` e `csrf.php` |
| **Error handling** | `error_log()` para DB errors, mensagem genérica ao usuário |
| **First user bootstrap** | `cadastrar_usuario.php` permitido sem login quando a tabela está vazia |

## Como Usar

### Gerenciar imóveis
1. Faça login no painel administrativo
2. No menu, clique em **Cadastrar Casas** para adicionar um novo imóvel
3. Selecione múltiplas fotos (JPG, JPEG, PNG, GIF - até 5MB cada)
4. Use **Listar Casas** para ver, editar ou excluir imóveis

### Gerenciar usuários
1. Clique em **Cadastrar Usuários** no menu
2. Use **Listar Usuários** para gerenciar administradores

## Desenvolvido por

**Silas Rosário**
