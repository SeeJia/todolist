# 使用官方 PHP 镜像
FROM php:8.1-apache

# 安装必要的 PHP 扩展
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql

# 设置工作目录
WORKDIR /var/www/html/

# 复制 composer.json 和 composer.lock（如果存在）
COPY composer.json composer.lock ./

# 安装 Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


