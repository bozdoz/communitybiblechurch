HOSTNAME=$1
DIR=$2
URL=$3
PHP=$4
SITENAME=$5
DBPASS='whocares6'

#
# Create a database and grant a user some permissions
#
echo "create database if not exists $DIR;" | sudo mysql -uroot -proot
echo "GRANT ALL ON $DIR.* TO '$DIR'@'localhost' IDENTIFIED BY '${DBPASS}';" | sudo mysql -uroot -proot
echo "flush privileges;" | sudo mysql -uroot -proot

#
# Install wp-cli
#
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar

sudo chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp

cd /var/www/${DIR}

sudo rm -f wp-config.php

# 
# wordpress download
# 
wp core download

sudo rm -f wp-config-sample.php

#
# create wp-config
#
wp config create \
	--dbname=${DIR} \
	--dbuser=${DIR} \
	--dbpass=${DBPASS} \
	--extra-php <<PHP
define('FS_METHOD','direct');
PHP

# 
# actual db install
#
wp core install \
	--url=$URL \
	--title="${SITENAME}" \
	--admin_user=bozdoz \
	--admin_password=whoReallyCar3s \
	--admin_email=howaboutben@gmail.com \
	--skip-email

# 
# activate theme 
# 
wp theme install twentyseventeen

# activate child theme
# wp theme activate twentyseventeen

# 
# plugins
# 
wp plugin install nginx-cache --activate
wp option update nginx_cache_path /var/www/${DIR}_cache/
#
# test post
#
# wp post create \
# 	--post_title='What is Vagrant and why should I care?' \
# 	--post_content='<p>OK, I totally get it now<p>' \
# 	--post_status=publish