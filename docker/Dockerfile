# Usar a imagem PHP-FPM base
FROM php:8.2-fpm

# Instalar dependências do sistema (como curl, git, unzip, etc.)
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libmariadb-dev \
    libmariadb-dev-compat \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql

# --- INÍCIO DA SEÇÃO NOVA: INSTALAR NODE.JS E NPM ---

# Adiciona o repositório oficial do NodeSource para obter a versão LTS (suporte de longo prazo)
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -

# Instala o Node.js (que já vem com o npm)
RUN apt-get install -y nodejs

# --- FIM DA SEÇÃO NOVA ---

# Baixar e instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Verificar a instalação do Composer
RUN composer --version

RUN pecl install redis && docker-php-ext-enable redis

# Definir o diretório de trabalho
WORKDIR /var/www/facilivery