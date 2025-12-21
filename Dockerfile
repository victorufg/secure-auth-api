# Usamos uma imagem leve do PHP com a versão 8.4 e FPM (FastCGI Process Manager)
FROM php:8.4-fpm-alpine

# Instalar dependências do sistema e extensões do PHP necessárias para o Laravel
# - libpq-dev é necessário para conectar no PostgreSQL
# - libzip-dev é para compactação de arquivos
# - zlib-dev, libpng-dev, libjpeg-turbo-dev são para manipulação de imagens (GD)
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# Instalar extensões do PHP (pdo_pgsql é a principal para o Postgres)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath gd mbstring

# Instalar o Composer (gerenciador de dependências do PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho dentro do container
WORKDIR /var/www

# Ajustar permissões para o usuário padrão do Alpine
RUN addgroup -S www && adduser -S www -G www
USER www

# Expor a porta 9000 para o PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
