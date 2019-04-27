# Execute this from inside Broadway whenever a new DB is pulled from production.
# This script will update all the DB values for your local enviornemnt.

# Run a limited search-replace.
wp search-replace content.cpr.org cpr.alley.test wp_blogs wp_site $(wp db tables "wp_*options" --network --url=content.cpr.org | paste -s -d ' ' -) --url=content.cpr.org
wp search-replace new.denverite.com denverite.cpr.alley.test wp_blogs wp_site $(wp db tables "wp_*options" --network --url=new.denverite.com | paste -s -d ' ' -) --url=new.denverite.com

# Update home.
wp option update home 'https://localhost:3001' --url=cpr.alley.test
wp option update home 'https://denverite.cpr.alley.test' --url=denverite.cpr.alley.test

# Update the alley user.
wp user update alley --user_pass=interactive --user_email=admin@local.test

# Flush the cache.
wp cache flush
