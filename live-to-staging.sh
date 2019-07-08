#!/bin/bash

# Execute this script from the command line: sh live-to-staging.sh
# Pass -l to limit the search-replace operation.
# Having Terminus set up is a prereq: https://pantheon.io/docs/terminus/install/

# Authenticate Terminus.
terminus auth:login

# Clone the DB and files from live to staging.
terminus env:clone-content cpr-mu.live staging --yes

# Determine the value of the limited flag.
limited=
while getopts 'l' option; do
	case $option in
		l)
			limited=1
			;;
	esac
done

# Run a search-replace.
if [ ! -z "$limited" ]; then
	echo "Limiting search/replace to only the essential tables."
	terminus wp cpr-mu.staging -- search-replace content.cpr.org content-staging.cpr.org wp_blogs wp_site $(terminus wp cpr-mu.live -- db tables "wp_*options" --network | paste -s -d ' ' -) --url=content.cpr.org
	terminus wp cpr-mu.staging -- search-replace denverite.com new-staging.denverite.com wp_blogs wp_site $(terminus wp cpr-mu.live -- db tables "wp_*options" --network | paste -s -d ' ' -) --url=denverite.com
else
	echo "Running a full search-replace."
	terminus wp cpr-mu.staging -- search-replace content.cpr.org content-staging.cpr.org --network --url=content.cpr.org
	terminus wp cpr-mu.staging -- search-replace denverite.com new-staging.denverite.com --network --url=denverite.com
fi

# Update home.
terminus wp cpr-mu.staging -- option update home 'https://cpr-staging.herokuapp.com' --url=content-staging.cpr.org

# Protect against accidental pushes to production NPR endpoint.
wp option delete ds_npr_api_push_url --url=content-staging.cpr.org

# Flush the cache.
terminus env:cc cpr-mu.staging
