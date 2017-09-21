@echo off

docker build -t tehraven/lbfb-discord-bot .

FOR /f "tokens=*" %%i IN ('docker ps -a -q') DO docker rm -f %%i

docker run -d --name web -e DNSMASQ_SERVERS="8.8.8.8,8.8.4.4" -e DNSMASQ_DEFAULT=0 -t tehraven/lbfb-discord-bot
docker ps
docker logs web