# PHP + Apache
FROM php:8.2-apache

# تثبيت امتداد PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# نسخ المشروع
COPY . /var/www/html/

# صلاحيات المجلد
RUN chown -R www-data:www-data /var/www/html

# تغيير منفذ Apache إلى 8080
EXPOSE 8080
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# تشغيل migrations قبل Apache تلقائيًا
CMD php /var/www/html/run_migrations.php && apache2-foreground
