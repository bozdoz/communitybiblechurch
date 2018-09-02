#!/bin/sh
set -o pipefail

sleep 1

wp core is-installed
status=$?

if [ $status -ne 0 ]; then
    echo 'waiting for db...'
    sleep 6
    wp core is-installed
    status=$?
fi

if [ $status -ne 0 ]; then
    echo "Initializing WordPress install!"
    wp core install \
        --url="localhost:$WORDPRESS_PORT" \
        --title="$TITLE" \
        --admin_user=$ADMIN_USER \
        --admin_password=$ADMIN_PASSWORD \
        --admin_email=$ADMIN_EMAIL \
        --skip-email

    wp config set WP_DEBUG $WP_DEBUG --raw
    wp theme activate $THEME
    # wp plugin install gutenberg --activate
    # wp plugin install nginx-cache --activate
    # wp option update nginx_cache_path /var/www/cache/
    
    wp post create --post_type=page \
        --post_title='About' \
        --post_status=publish \
        --post_content='About Us'
    
    wp post create --post_type=page \
        --post_title='Contact' \
        --post_status=publish \
        --post_content='Contact Us'
    
    wp post create --post_type=page \
        --post_title='Events' \
        --post_status=publish \
        --post_content='Events'

fi
