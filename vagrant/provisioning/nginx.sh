HOSTNAME=$1
DIR=$2
URL=$3
PHP=$4
#
# setup nginx fast cache (works)
#

# 
# get SSL
# 
sudo mkdir /etc/nginx/ssl
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/nginx/ssl/nginx.key \
    -out /etc/nginx/ssl/nginx.crt \
    -subj "/C=CA/ST=NS/L=Halifax/O=bozdoz/OU=HQ/CN=$HOSTNAME" 

sudo cat > /etc/nginx/sites-available/${DIR}.conf <<EOF
fastcgi_cache_path /var/www/${DIR}_cache levels=1:2 keys_zone=WORDPRESS:100m inactive=60m;
fastcgi_cache_key "\$scheme\$request_method\$host\$request_uri";
fastcgi_cache_use_stale error timeout invalid_header http_500;
fastcgi_ignore_headers Cache-Control Expires Set-Cookie;

server {
    listen 80;
    listen [::]:80 ssl ipv6only=on;

    server_name $HOSTNAME;

    return 302 https://${HOSTNAME}\$request_uri;
}

server {
    listen 443 ssl;
    listen [::]:443 ssl ipv6only=on;

    server_name $HOSTNAME;

    ssl_certificate /etc/nginx/ssl/nginx.crt;
    ssl_certificate_key /etc/nginx/ssl/nginx.key;

    access_log /var/log/nginx-access.log;
    error_log /var/log/nginx-error.log; 

    root /var/www/$DIR;
    index index.php;

    set \$skip_cache 0;
    if (\$request_method = POST) {set \$skip_cache 1;}
    if (\$query_string != "") {set \$skip_cache 1;}
    if (\$request_uri ~* "/wp-admin/|/xmlrpc.php|/wp-.*.php|/feed/|index.php|sitemap(_index)?.xml") {set \$skip_cache 1;}
    if (\$http_cookie ~* "comment_author|wordpress_[a-f0-9]+|wp-postpass|wordpress_no_cache|wordpress_logged_in") {set \$skip_cache 1;}

    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_pass unix:/run/php/php${PHP}-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_cache_bypass \$skip_cache;
        fastcgi_no_cache \$skip_cache;
        fastcgi_cache WORDPRESS;
        fastcgi_cache_valid 200 60m;
        add_header X-Cache-Status \$upstream_cache_status;
    }
}
EOF

sudo ln -sf /etc/nginx/sites-available/${DIR}.conf /etc/nginx/sites-enabled/

sudo mkdir -p /var/www/${DIR}_cache