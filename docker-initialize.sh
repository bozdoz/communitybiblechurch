#!/bin/sh

wp theme install twentyseventeen
wp theme activate commbible

wp post create --post_type=page \
    --post_title='About Us' \
    --post_status=publish \
    --post_content='About Us'

wp post create --post_type=page \
    --post_title="Men's Ministry" \
    --post_status=publish \
    --post_content="Men's Ministry"

wp post create --post_type=page \
    --post_title="Women's Ministry" \
    --post_status=publish \
    --post_content="Women's Ministry"

wp post create --post_type=page \
    --post_title="Young Adults" \
    --post_status=publish \
    --post_content="Young Adults"

wp post create --post_type=page \
    --post_title="Children's Ministry" \
    --post_status=publish \
    --post_content="Children's Ministry"

wp post create --post_type=page \
    --post_title="What We Believe" \
    --post_status=publish \
    --post_content="What We Believe"

echo '<h1>(Apr 99, 2099)</h1>Our Sunday Service: <iframe src="https://www.youtube.com/embed/QTCYF7N9Ql4" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>' | wp post create \
    --post_type=page \
    --post_title='Home' \
    --post_status=publish \
    -
