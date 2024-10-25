# 使用官方 PHP 镜像
FROM php:8.1-apache

# 复制项目文件到容器内
COPY . /var/www/html/

# 设置文件夹权限（可选）
RUN chown -R www-data:www-data /var/www/html

# 如果有 Composer 依赖，请取消注释下面的行
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# RUN composer install

# 暴露容器的80端口
EXPOSE 80
