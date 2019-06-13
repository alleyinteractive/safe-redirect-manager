inc/enqueue.php
===============

.. php:function:: largo_enqueue_js()

   Enqueue our core javascript and css files

   :since: 1.0

   :global: LARGO_DEBUG

.. php:function:: largo_gallery_enqueue()

   Enqueue Largo gallery CSS & JS

   :since: 0.5.5.3

.. php:function:: largo_enqueue_child_theme_css()

   Enqueue Largo child theme CSS

   :since: 0.5.4

.. php:function:: largo_enqueue_admin_scripts()

   Enqueue our admin javascript and css files

   :global: LARGO_DEBUG

.. php:function:: largo_header_js()

   Determine which size of the banner image to load based on the window width

   :since: 1.0

.. php:function:: largo_footer_js()

   Additional scripts to load in the footer (mostly for various social widgets)

   :since: 1.0

.. php:function:: largo_google_analytics()

   Add Google Analytics code to the footer

   You need to add your GA ID to the theme settings for this to work.

   Through version 0.5.5.4, this function output a Google Analytics
   tag even if the site didn't have GA configured in the theme. This
   tag was used to send GA tracking events to properties for INN and
   for the Largo Project.

   In this current version of the plugin, it only outputs a GA tag
   if you've added a GA ID to the theme settings. If you're using a different
   GA script, leave that setting blank.

   If you're using Largo, please send us an email to say hi!
   https://labs.inn.org/contact/

   :since: 0.3

.. php:function:: largo_gutenberg_frontend_css_js()

   Enqueue Largo's Gutenberg-supporting stylesheets and scripts, for the frontend

   :since: 0.6

.. php:function:: largo_gutenberg_editor_css_js()

   Enqueue Largo's Gutenberg-supporting stylesheets and scripts, for the admin editor

   :since: 0.6

   :see: https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts