
# Projeto AgendService
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/WBG-Coach/coach-admin/blob/main/LICENSE.md)

Projeto do curso de full cycle

## Tecnologias Utilizadas
- node: v16.19.0
- npm: v8.19.3
- php: v8.2.33
- mysql: v8.0
- laravel: v10.1
- 

## Passo a passo
1 - Rode o comando make up_build. Isso constrói e inicia todos os containers (PHP, MySQL, Node, etc.)
2 - Rode o comando make composer_install. Instala as dependências do Laravel via Composer
3 - Rode o comando make npm_install. Instala as dependências de front-end.
4 - Rode o comando make env. Isso cria o arquivo de configurações.
5 - Rode o comando make key. Gera a chave APP_KEY necessária para o Laravel
6 - Rode o comando make migrate. Cria as tabelas no MySQL conforme definido nas migrações
