# Sistema de Agendamento de Eventos

## Integrantes

| Nome | Matrícula |
|---|---|
| Pedro Henrique Campos Moreira | 8745 |
| Paulo Roberto Carvalho | 8158 |
| Gabriel Alves Rodrigues | 8125 |
| Vinicius Matoso Dias | 8785 |
| Juan Rodrigues Ferreira | 8754 |

Trabalho final da disciplina **SIN 493 - Introdução à Programação Web** (UFV).

## Objetivo

Permitir que usuários cadastrados criem eventos e agendem a própria participação neles. Cada usuário pode consultar e cancelar seus agendamentos.

## Tecnologias

- HTML5
- CSS3 (um único arquivo: `public/css/estilo.css`)
- JavaScript puro (um único arquivo: `public/js/scripts.js`)
- PHP puro, com sessões (`session_start`, `$_SESSION`), `password_hash()` e `password_verify()`
- Armazenamento em arquivos JSON (sem banco de dados)

Não usa frameworks, bibliotecas, Node.js, banco de dados nem CDN externa.

## Funcionalidades

- **Cadastro de usuário:** nome, e-mail, senha e confirmação. A senha é salva com `password_hash()` e o e-mail não pode se repetir.
- **Login e logout:** autenticação com `password_verify()` e sessão. As páginas internas só abrem para usuários logados.
- **Página inicial:** boas-vindas com o nome do usuário, menu de navegação e os próximos eventos.
- **Criar evento:** nome, descrição, data, horário e local. A data e o horário não podem estar no passado.
- **Lista de eventos:** nome, data, horário, local e botão de detalhes.
- **Detalhes e agendamento:** o mesmo usuário não pode agendar o mesmo evento duas vezes.
- **Meus agendamentos:** lista apenas os eventos do usuário logado e permite cancelar, com confirmação em JavaScript.
- **Acabamento:** mensagens de sucesso e erro, botão mostrar/ocultar senha e layout responsivo.
- **Validação:** todo formulário é validado no JavaScript e de novo no PHP.

## Estrutura do projeto

```
public/                        única pasta acessível pelo navegador
  index.php                    redireciona para login ou início
  login.php, logout.php        entrada e saída do sistema
  cadastro.php                 cadastro de usuário
  inicio.php                   página inicial
  eventos.php                  lista de eventos
  criar-evento.php             formulário de novo evento
  detalhes.php                 dados de um evento
  agendar.php                  grava o agendamento
  meus-agendamentos.php        agendamentos do usuário logado
  cancelar.php                 cancela um agendamento
  css/estilo.css               estilos
  js/scripts.js                validações e interações
funcoes.php                    funções compartilhadas (JSON, login, mensagens, validação)
dados/                         usuarios.json, eventos.json, agendamentos.json
  .htaccess                    bloqueia o acesso à pasta no Apache
```

Os arquivos JSON ficam fora de `public/`. Assim o navegador não consegue abrir os dados (nomes, e-mails e hashes de senha).

## Como executar

Requisito: PHP 8 ou superior instalado (`php --version`).

1. Abra um terminal na pasta raiz do projeto.
2. Inicie o servidor embutido do PHP servindo só a pasta `public`:

   ```
   php -S localhost:8000 -t public
   ```

3. Acesse <http://localhost:8000> no navegador.

O servidor precisa de permissão para gravar na pasta `dados/`.

## Usuário de teste

| E-mail | Senha |
|--------|-------|
| teste@exemplo.com | 123456 |

Também é possível criar novas contas na tela de cadastro.

## Telas

<!-- Adicione os prints na pasta prints/ e ajuste os nomes abaixo -->

| Tela | Imagem |
|------|--------|
| Cadastro | ![Cadastro](prints/cadastro.png) |
| Login | ![Login](prints/login.png) |
| Página inicial | ![Início](prints/inicio.png) |
| Criar evento | ![Criar evento](prints/criar-evento.png) |
| Lista de eventos | ![Eventos](prints/eventos.png) |
| Detalhes do evento | ![Detalhes](prints/detalhes.png) |
| Meus agendamentos | ![Meus agendamentos](prints/meus-agendamentos.png) |
