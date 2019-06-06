#!/bin/bash

# Execute this script from the command line: sh live-to-dev.sh
# Pass -l to limit the search-replace operation.
# Having Terminus set up is a prereq: https://pantheon.io/docs/terminus/install/

# Authenticate Terminus.
terminus auth:login

# Clone the DB and files from live to dev.
terminus env:clone-content cpr-mu.live dev --yes

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
	terminus wp cpr-mu.dev -- search-replace content.cpr.org content-dev.cpr.org wp_blogs wp_site $(terminus wp cpr-mu.live -- db tables "wp_*options" --network | paste -s -d ' ' -) --url=content.cpr.org
	# terminus wp cpr-mu.dev -- search-replace new.denverite.com new-dev.denverite.com wp_blogs wp_site $(terminus wp cpr-mu.live -- db tables "wp_*options" --network | paste -s -d ' ' -) --url=new.denverite.com
else
	echo "Running a full search-replace."
	terminus wp cpr-mu.dev -- search-replace content.cpr.org content-dev.cpr.org --network --url=content.cpr.org
	# terminus wp cpr-mu.dev -- search-replace new.denverite.com new-dev.denverite.com --network --url=new.denverite.com
fi

# Update home.
terminus wp cpr-mu.dev -- option update home 'https://cpr-dev.herokuapp.com' --url=content-dev.cpr.org
# terminus wp cpr-mu.dev -- option update home 'https://denverite-dev.herokuapp.com' --url=new-dev.denverite.com

# Flush the cache.
terminus env:cc cpr-mu.dev
