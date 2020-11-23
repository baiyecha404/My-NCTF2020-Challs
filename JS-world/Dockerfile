FROM node:14-alpine

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories && apk update

RUN mkdir -p /app && chown -R root:root /app

WORKDIR /app

COPY web .

RUN npm install --registry https://registry.npm.taobao.org/

RUN chown -R nobody:nobody /app/sessions &&\
    chown -R nobody:nobody /app/views/templates

USER nobody

CMD ["npm","start"]

