#!/bin/bash
sudo apt-get update
sudo apt-get upgrade

# SHAIRPORT INSTALLATION
sudo apt-get install avahi-utils build-essential chkconfig git libao-dev libavahi-client-dev libcrypt-openssl-rsa-perl libio-socket-inet6-perl libssl-dev libwww-perl pkg-config

git clone -b 1.0-dev git://github.com/abrasive/shairport.git

cd shairport

sudo ./configure && sudo make && sudo make install

shairport -a 'Raspberry JAM'

# WEBSERVICE INSTALLATION
sudo apt-get install apache2

sudo apt-get install php5 libapache2-mod-php5 -y

sudo apt-get install mysql-server --fix-missing

sudo apt-get install mysql-client php5-mysql

# SAMBA INSTALLATION
sudo apt-get install samba samba-common-bin

# CHROMIUM INSTALL FOR KIOSK
sudo apt-get install chromium x11-xserver-utils unclutter

# Rplay installation
sudo apt-get install libao-dev avahi-utils libavahi-compat-libdnssd-dev libva-dev youtube-dl

wget -O rplay-1.0.1-armhf.deb http://www.vmlite.com/rplay/rplay-1.0.1-armhf.deb

sudo dpkg -i rplay-1.0.1-armhf.deb #S1377T8072I7798N4133R

# OWNCLOUD INSTALLATION
sudo apt-get install apache2 php5 php5-json php5-gd php5-sqlite curl libcurl3 libcurl4-openssl-dev php5-curl php5-gd php5-cgi php-pear php5-dev build-essential libpcre3-dev php5 libapache2-mod-php5 php-apc gparted

sudo pecl install apc

sudo openssl genrsa -des3 -out server.key 1024; sudo openssl rsa -in server.key -out server.key.insecure;sudo openssl req -new -key server.key -out server.csr;sudo openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt;sudo cp server.crt /etc/ssl/certs;sudo cp server.key /etc/ssl/private;sudo a2enmod ssl;sudo a2ensite default-ssl

wget http://mirrors.owncloud.org/releases/owncloud-4.5.1.tar.bz2

sudo tar -xjf owncloud-4.5.1.tar.bz2

sudo cp -r owncloud /var/www

sudo chown -R www-data:www-data /var/www/owncloud/

sudo nano /var/www/owncloud/.htaccess

# SQUEEZELITE PLAYER
sudo apt-get install -y libflac-dev libfaad2 libmad0

mkdir squeezelite
cd squeezelite
wget -O squeezelite-armv6hf http://ralph_irving.users.sourceforge.net/pico/squeezelite-armv6hf-noffmpeg

sudo mv squeezelite-armv6hf /usr/bin
sudo chmod a+x /usr/bin/squeezelite-armv6hf

# START SQUEEZELITE
sudo /usr/bin/squeezelite-armv6hf -o front:CARD=Set,DEV=0

# RTP client

# Pulse audio

# TOR ROUTER

# TORRENT SERVER

# OPEN VPN
