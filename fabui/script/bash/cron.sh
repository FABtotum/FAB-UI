#!/bin/bash

cd /var/www/cron
sudo php blog_feed.php >> /var/log/cron.log
sudo php faq.php >> /var/log/cron.log
sudo php instagram_feed.php >> /var/log/cron.log
sudo php twitter_feed.php >> /var/log/cron.log