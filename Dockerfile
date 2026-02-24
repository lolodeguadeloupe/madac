FROM wordpress:latest

# Copy MADAC theme
COPY wp-content/themes/madac /var/www/html/wp-content/themes/madac

# Install WP-CLI
RUN curl -sO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

EXPOSE 80
