#!/bin/bash

# Execute this script from the command line: sh live-to-test.sh
# Pass -l to limit the search-replace operation.
# Having Terminus set up is a prereq: https://pantheon.io/docs/terminus/install/

# Authenticate Terminus.
terminus auth:login

# Clone the DB and files from live to test.
terminus env:clone-content cpr-mu.live test --yes

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
	terminus wp cpr-mu.test -- search-replace content.cpr.org content-test.cpr.org wp_blogs wp_site $(terminus wp cpr-mu.live -- db tables "wp_*options" --network | paste -s -d ' ' -) --url=content.cpr.org
	terminus wp cpr-mu.test -- search-replace denverite.com new-test.denverite.com wp_blogs wp_site $(terminus wp cpr-mu.live -- db tables "wp_*options" --network | paste -s -d ' ' -) --url=denverite.com
else
	echo "Running a full search-replace."
	terminus wp cpr-mu.test -- search-replace content.cpr.org content-test.cpr.org --network --url=content.cpr.org
	terminus wp cpr-mu.test -- search-replace denverite.com new-test.denverite.com --network --url=denverite.com
fi

# Update home.
terminus wp cpr-mu.test -- option update home 'https://cpr-test.herokuapp.com' --url=content-test.cpr.org

# Flush the cache.
terminus env:cc cpr-mu.test
