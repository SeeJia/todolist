# 使用官方 PHP 镜像
FROM php:8.1-apache

# 设置工作目录
WORKDIR /var/www/html/

# 复制 composer.json 和 composer.lock（如果存在）
COPY composer.json composer.lock ./

# 安装 Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 安装依赖
RUN composer install --no-dev --optimize-autoloader

# 复制项目文件
COPY . .

# 设置文件夹权限（可选）
RUN chown -R www-data:www-data /var/www/html

# 暴露容器的80端口
EXPOSE 80

