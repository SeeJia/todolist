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
COPY --from=composer:2.8.1 /usr/bin/composer /usr/local/bin/composer

# 复制项目文件
COPY . .

# 设置文件夹权限（可选）
RUN chown -R www-data:www-data /var/www/html

# 暴露容器的80端口
EXPOSE 80

RUN composer install --no-scripts --no-plugins

