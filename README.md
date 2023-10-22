# Sapherna
An avatar community.

## Setup
Setup apache/MySQL. tons of tuts for that. Gotchas are below. Not the most secure setup but it'll get her done.
```
# rewrite
sudo chmod -R 755 www/**/*
a2enmod rewrite
service apache2 restart

# mcrypt
sudo apt-get install php5-mcrypt
cd /etc/php5/cli/conf.d
sudo ln -s ../../mods-available/mcrypt.ini 20-mcrypt.ini
sudo service apache2 restart
sudo apt-get install php5-mcrypt
sudo php5enmod mcrypt
sudo service apache2 restart

# curl
sudo apt-get install php5-curl
```
