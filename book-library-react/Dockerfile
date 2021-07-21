FROM node:alpine
WORKDIR '/srv/app'
COPY package.json .
RUN npm install
RUN npm install react-router-dom
COPY . .
RUN npm run build
 
FROM nginx
EXPOSE 80
COPY --from=0 /srv/app /usr/share/nginx/html