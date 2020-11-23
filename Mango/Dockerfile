FROM node:12

RUN mkdir -p /app && chown -R root:root /app 

WORKDIR /app

COPY ./web ./

RUN npm install --registry https://registry.npm.taobao.org/ 

EXPOSE 8084

CMD [ "npm", "start" ]

