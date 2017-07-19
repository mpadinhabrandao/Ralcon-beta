FROM centos:centos6
MAINTAINER MÁRCIO PADINHA BRANDÃO <mpadinhabrandao@gmail.com>

RUN yum -y update
RUN yum -y install httpd gcc make git mysql-server mysql mysql-client

RUN rpm -Uvh http://ftp.iij.ad.jp/pub/linux/fedora/epel/6/x86_64/epel-release-6-8.noarch.rpm
RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
RUN yum -y install --enablerepo=remi --enablerepo=remi-php56 	php php-opcache php-devel php-mbstring \
								php-mcrypt php-mysqlnd php-pecl-xdebug php-pecl-xhprof pcre-devel php-gd php-gmp


RUN yum -y install --enablerepo=remi --enablerepo=remi-php56 php-fpm
RUN yum -y install --enablerepo=remi --enablerepo=remi-php56 nginx

ADD nginx.conf /etc/nginx/nginx.conf
ADD default.conf /etc/nginx/sites-enabled/default.conf

# RUN echo "daemon off;" >> /etc/nginx/nginx.conf

RUN git clone --depth=1 git://github.com/phalcon/cphalcon.git /root/phalcon
RUN cd /root/phalcon/build && ./install 64bits
RUN echo extension=/usr/lib64/php/modules/phalcon.so >> /etc/php.d/phalcon.ini

RUN yum -y install wget && cd /tmp && wget http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz && tar xfz ioncube_loaders_lin_x86-64.tar.gz
RUN cd /tmp/ioncube && cp ioncube_loader_lin_5.6.so /usr/lib64/php/modules/ioncube_loader_lin_5.6.so
RUN echo zend_extension=/usr/lib64/php/modules/ioncube_loader_lin_5.6.so >> /etc/php.ini

RUN echo date.timezone = "Europe/Lisbon" >> /etc/php.ini
RUN sed -i "s/short_open_tag = .*/short_open_tag = On/" /etc/php.ini
RUN sed -i "s/apache/nginx/" /etc/php-fpm.d/www.conf
RUN chown -R nginx:nginx /var/www/html
RUN usermod -G nginx nginx
RUN chmod -R 777 /var/www/html

# ENTRYPOINT /usr/sbin/nginx -c /etc/nginx/nginx.conf && service php-fpm start
ENTRYPOINT service php-fpm start && /usr/sbin/nginx -c /etc/nginx/nginx.conf
EXPOSE 80 443 9000

