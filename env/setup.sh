#!/bin/bash

root=$( dirname $( wp config path ) )

wp theme activate wporg-photos-2024

wp option update blogname "Photos"
wp option update blogdescription "Choose from a growing collection of free, CC0-licensed photos to customize and enhance your WordPress website."
wp option update posts_per_page 24

wp import "${root}/env/data/wporg-photos-pages.xml" --authors=create
wp import "${root}/env/data/wporg-photos-photos.xml" --authors=create

wp rewrite structure '/%postname%/'
wp rewrite flush --hard
