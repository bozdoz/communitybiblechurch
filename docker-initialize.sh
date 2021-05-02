#!/bin/sh

wp theme install twentyseventeen
wp theme activate commbible

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

echo '<h1>(Apr 18, 2021)</h1>Our Sunday Service, Apr 18, 2021: <iframe src="https://www.youtube.com/embed/QTCYF7N9Ql4" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>' | wp post create \
    --post_type=page \
    --post_title='Home' \
    --post_status=publish \
    -
