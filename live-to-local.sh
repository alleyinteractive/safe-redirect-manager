# Execute this from inside Broadway whenever a new DB is pulled from production.
# This script will update all the DB values for your local enviornemnt.

# Run a limited search-replace.
wp search-replace content.cpr.org cpr.alley.test wp_blogs wp_site $(wp db tables "wp_*options" --network --url=content.cpr.org | paste -s -d ' ' -) --url=content.cpr.org
wp search-replace denverite.com denverite.cpr.alley.test wp_blogs wp_site $(wp db tables "wp_*options" --network --url=denverite.com | paste -s -d ' ' -) --url=denverite.com

# Update home for headless CPR.
wp option update home 'https://localhost:3001' --url=cpr.alley.test

# Update the alley user.
wp user update alley --user_pass=interactive --user_email=admin@local.test

# Protect against accidental pushes to production NPR endpoint.
wp option delete ds_npr_api_push_url --url=cpr.alley.test

# Setup SFP.
wp option update sfp_url https://content.cpr.org/wp-content/uploads/ --url=cpr.alley.test
wp option update sfp_url https://i0.wp.com/wp-denverite.s3.amazonaws.com/wp-content/uploads/sites/4/ --url=denverite.cpr.alley.test

# Activate plugins.
wp plugin activate stage-file-proxy --network --url=cpr.alley.test

# Flush the cache.
wp cache flush
