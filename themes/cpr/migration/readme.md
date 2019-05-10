# Export JSON from migration server

Or take a shortcut and download it from Drive, https://drive.google.com/open?id=1kEuBaL2ojIhjb3eapt-cGwEWOSX4RWfy

Login to the migration server and execute the `node-to-json` command.
* `ssh ec2-user@wp.cpr.alley.ws`
* `cd /var/www/drupal/`
* `sudo /usr/local/bin/drush ntoj` (ntoj is an alias for nodes-to-json)

It'll run and you'll see output such as
```
Exporting taxonomy.
Exporting users.
```
You can then find all the JSON files in directories under /var/www/exports/ew/

Another helpful command:
`drush pm-info` - shows you info about each module, e.g. the Alley Migrator module has this info:
```
Extension        :  ai_migrator
 Project          :  Unknown
 Type             :  module
 Title            :  Alley Interactive Exporter
 Description      :  JSON Exporting Tool
 Package          :  Alley Interactive Exporter
 Core             :  7.x
 PHP              :  5.2.4
 Status           :  enabled
 Path             :  sites/all/modules/custom/amt_drupal
 Schema version   :  module has no schema
 Files            :  none
 Requires         :  none
 Required by      :  none
 Permissions      :  none
 Configure        :  None
```

Move the exported folder (which should contain subfolders for each content type), and drop it in `/cpr/migration/data/` resulting in the following structure,
```
+--- cpr
|   +--- migration
|   +--- cli
|   +--- data
|       \--- alert
|       \--- announcement
|       \--- etc
|   +--- feeds
```

# Getting data on production
You will need to FTP into Pantheon and upload these files manually, same as if it were local.

# Available Feeds
* `category` - Migrate `topics` to categories.
* `document` - Migrate `document` to attachments.
* `guest-author` - Migrate `person` to Guest Authors.
* `image` - Migrate `image` to attachments.
* `job` - Migrate `employment_opportunity` to job posts.
* `page` - Migrate `page` to pages.
* `post-tag` - Migrate `taxonomy` to tags.
* `service` - Migrate `service` to section terms.
* `story` - Migrate `story` to posts.
* `underwriter` - Migrate `underwriter` to underwriter posts.
* `user` - Migrate `users` to WP users.

# Sync Locally Example
`wp alleypack sync category`

# Sync Pantheon Example
`terminus wp cpr-mu.live -- alleypack sync category --url=content.cpr.org`

# Protip
While the feeds should be intelligent enough to create stubs, you'll probably want to run the migration in a specific order.

Users
* `wp alleypack sync user`
* `wp alleypack sync guest-author`

Terms
* `wp alleypack sync category`
* `wp alleypack sync post-tag`
* `wp alleypack sync service`

Attachments
* `wp alleypack sync document`
* `wp alleypack sync image`

Posts
* `wp alleypack sync story`
* `wp alleypack sync page`
* `wp alleypack sync job`
* `wp alleypack sync underwriter`
