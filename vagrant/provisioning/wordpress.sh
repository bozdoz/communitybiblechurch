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
define('WP_DEBUG', true);
PHP

# 
# actual db install
#
wp core install \
	--url=$URL \
	--title="${SITENAME}" \
	--admin_user=admin \
	--admin_password=password \
	--admin_email=nobody@example.com \
	--skip-email

# 
# activate theme 
# 
wp theme install twentyseventeen
wp theme activate commbible

# activate child theme
# wp theme activate twentyseventeen

# 
# plugins
# 
wp plugin install gutenberg --activate
# wp plugin install nginx-cache --activate
# wp option update nginx_cache_path /var/www/${DIR}_cache/

# test post
#
wp post create \
	--post_title='About' \
	--post_content='<p>About us<p>' \
	--post_status=publish
	--post_type=page

wp post create \
	--post_title='News' \
	--post_content='<p>News for what matters<p>' \
	--post_status=publish
	--post_type=page

wp post create \
	--post_title='Contact' \
	--post_content='<p>Contact us<p>' \
	--post_status=publish
	--post_type=page