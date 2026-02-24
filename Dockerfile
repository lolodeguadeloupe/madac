FROM wordpress:latest

# Copy MADAC theme to WordPress source directory
# (the entrypoint copies from /usr/src/wordpress to /var/www/html on fresh install,
#  but won't overwrite existing volume data — so we also sync at startup via CMD)
COPY wp-content/themes/madac /usr/src/wordpress/wp-content/themes/madac

# Install WP-CLI
RUN curl -sO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

EXPOSE 80

# On start: sync theme into the volume, then launch Apache
CMD ["bash", "-c", "cp -rf /usr/src/wordpress/wp-content/themes/madac /var/www/html/wp-content/themes/madac && apache2-foreground"]
