@echo off

docker build -t tehraven/lbfb-discord-bot .

FOR /f "tokens=*" %%i IN ('docker ps -a -q') DO docker rm -f %%i

docker run -d --name web -p 80:80 -t tehraven/lbfb-discord-bot
  
docker ps
docker logs -f web