FROM tehraven/lbfb-discord-server
MAINTAINER "https://github.com/EVE-LBFB"
# BUILDS tehraven/lbfb-discord-bot

RUN apk add --update \
    git
    
COPY web /var/www
COPY root /
WORKDIR /var/www
RUN composer install