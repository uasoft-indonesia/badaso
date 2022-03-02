FROM gitpod/workspace-mysql

RUN sudo apt update -y

# Install ppa:ondrej/php PPA
RUN sudo apt install -y software-properties-common
RUN sudo add-apt-repository ppa:ondrej/php -y
RUN sudo apt update -y

# Install PHP 8
# RUN sudo apt install -y php-pear libapache2-mod-php
RUN sudo apt install -y php-common php-cli 2>/dev/null; true
RUN sudo apt install -y php-bz2 php-zip php-curl php-gd php-mysql php-xml php-dev php-mbstring php-bcmath 2>/dev/null; true
RUN sudo php -v
RUN sudo php -m

# Install Composer 2.2.6
RUN sudo wget https://getcomposer.org/download/2.2.6/composer.phar
RUN sudo sudo chmod +x composer.phar
RUN sudo sudo cp composer.phar /usr/bin/composer
RUN sudo sudo mv composer.phar /usr/local/bin/composer

# Install Node Js
RUN sudo curl -sL https://deb.nodesource.com/setup_14.x | sudo bash -
RUN sudo apt -y install nodejs 2>/dev/null; true
RUN sudo node -v

USER gitpod
