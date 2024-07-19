FROM ghcr.io/ghostwriter/php:8.3-pcov

WORKDIR /srv/app

COPY . .

RUN set -eux && composer install --no-interaction --no-progress

CMD ["composer", "check"]
