inc/deprecated.php
==================

.. php:function:: largo_cached_nav_menu()

   Wrapper for wp_nav_menu() that previously cached nav menus. Removed caching mechanism and
   changed function name to largo_nav_menu in 0.4. See largo_nav_menu.

   :since: 0.3

   :deprecated: 0.4

   :deprecated: Use $argo_nav_menu()

   :see: largo_nav_menu()
   :param array $args:

.. php:function:: largo_fb_user_is_followable()

   Deprecated function to check whether a Facebook user/page was followable.

   This function used to use the Facebook Follow button's HTML markup
   https://developers.facebook.com/docs/archive/docs/plugins/follow-button/
   to determine whether a page or user could be followed. The button was
   deprecated by Facebook on February 5, 2018, and as a result, this function
   stopped working.

   There are no plans to provide similar functionality in the future, because
   the relevant Facebook Graph API calls require Facebook API tokens,
   and it seems excessive to require theme users to get one just for this check.

   :param string $username: a valid Facebook username or page name. They're generally indistinguishable, except pages get to use '-'

   :returns: bool $alse