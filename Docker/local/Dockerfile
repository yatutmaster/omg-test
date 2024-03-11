ARG DEBIAN_RELEASE=bookworm
FROM debian:${DEBIAN_RELEASE}

# basic setup
RUN apt update && apt --no-install-recommends -y install \
  lsb-release apt-transport-https ca-certificates openssl wget \
  gnupg2 locales file curl unzip git git-lfs nano make \
  && echo 'en_US.UTF-8 UTF-8' > /etc/locale.gen \
  && echo 'ru_RU.UTF-8 UTF-8' >>/etc/locale.gen \
  && locale-gen \
  && rm -rf /var/lib/apt/lists/*

# install PHP
ARG PHP_RELEASE=8.3
RUN curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /usr/share/keyrings/sury.gpg \
  && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/sury.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/sury.list \
  && apt update && apt --no-install-recommends -y install \
  php${PHP_RELEASE}-xml \
  php${PHP_RELEASE}-intl \
  php${PHP_RELEASE}-tidy \
  php${PHP_RELEASE}-zip \
  php${PHP_RELEASE}-gd \
  php${PHP_RELEASE}-redis \
  php${PHP_RELEASE}-curl \
  php${PHP_RELEASE}-mbstring \
  php${PHP_RELEASE}-excimer \
  && rm -rf /var/lib/apt/lists/*

# install nginx unint
RUN curl --output /usr/share/keyrings/nginx-keyring.gpg https://unit.nginx.org/keys/nginx-keyring.gpg \
  && echo "deb [signed-by=/usr/share/keyrings/nginx-keyring.gpg] https://packages.nginx.org/unit/debian/ $(lsb_release -sc) unit" > /etc/apt/sources.list.d/unit.list \
  && apt update \
  && apt --no-install-recommends -y install unit unit-php \
  && rm -rf /var/lib/apt/lists/*

RUN apt update && apt --no-install-recommends -y install supervisor cron net-tools procps psmisc \
  && rm -rf /var/lib/apt/lists/*


RUN wget -qO - https://getcomposer.org/download/latest-stable/composer.phar > /usr/local/bin/composer \
  && chmod 0755 /usr/local/bin/composer

# create php symlink
RUN  ln -s /etc/php/${PHP_RELEASE} /etc/php/current

COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

COPY crontab /etc/cron.d/crontab
RUN chmod 0600 /etc/cron.d/crontab
RUN touch /var/log/cron.log
RUN crontab /etc/cron.d/crontab

COPY unit-conf.json /
COPY unit.sh /

EXPOSE 8080
CMD /usr/bin/supervisord