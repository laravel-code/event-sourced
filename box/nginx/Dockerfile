FROM nginx:latest

#Get the user_id and group_id 'id -u' and 'id -g'
#RUN addgroup --gid 1000 user
#RUN adduser --disabled-password --gecos '' --uid 1000 --gid 1000 user

RUN apt-get update && apt-get install -y
COPY default.conf /etc/nginx/conf.d/


