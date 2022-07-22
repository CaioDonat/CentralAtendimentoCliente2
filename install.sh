#!/bin/bash

#execute chmod +x install
# execute ./install

sudo apt-get update -y
sudo apt-get full-upgrade -f

echo "Removendo PHP existente"
sudo apt remove php
sudo apt remove php composer
echo "instalando ambiente de desenvolvimento";
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.1

sudo apt install vim git curl php-curl php-xml php-mbstring php-mysql

function nodejs(){
echo "instalando nodejs";
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
source ~/.bashrc
nvm install v16.15.1
npm install -g npm@8.13.2
}

function composer(){
echo "Instalando composer";
cd Downloads
wget https://getcomposer.org/download/2.3.10/composer.phar
chmod +x composer.phar
sudo mv composer.phar /usr/local/bin/composer

}

cd ~/Documents

git clone https://github.com/CaioDonat/CAC-DDS-7.git
cd CAC-DDS-7
git checkout servidor
echo "atualizando composer";
composer reinstall psr/log
composer update
composer upgrade
composer install
npm install
npm run dev


cd ~/
