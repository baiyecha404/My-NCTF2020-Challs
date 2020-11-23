FROM node:14-alpine

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories &&\
    apk update && apk add supervisor && rm /usr/bin/nc 

RUN mkdir -p /app && chown -R root:root /app

WORKDIR /app

COPY web .

COPY scripts /tmp

RUN chmod 744 privatekey.pem &&\
    npm install --registry https://registry.npm.taobao.org/ 

COPY supervisord.conf /etc/supervisord.conf

RUN cd /tmp && mv start.sh /start.sh && mv *.sh /home/node &&\
    chmod +x /start.sh && chmod +x /home/node/*.sh

ENTRYPOINT ["/start.sh"]
