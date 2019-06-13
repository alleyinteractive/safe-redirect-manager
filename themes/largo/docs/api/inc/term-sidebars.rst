inc/term-sidebars.php
=====================

.. php:method:: Largo_Term_Sidebars::__construct()

      Constructor

   .. php:method:: Largo_Term_Sidebars::get_sidebar_taxonomies()

      Return a list of taxonomies that term sidebars are enabled for

      :filter: largo_get_sidebar_taxonomies

      :returns: Array $f taxonomy slugs

   .. php:method:: Largo_Term_Sidebars::display_fields()

      Renders the form fields on the term's edit page

      :param WP_Term $term: The term for which the fields should be displayed.

      :returns: nothing

   .. php:method:: Largo_Term_Sidebars::display_add_new_field()

      Renders the form fields for the new form creation on the term listing paga

      :param string $taxonomy: unused.

   .. php:method:: Largo_Term_Sidebars::admin_enqueue_scripts()

      Attach the Javascript and Stylesheets to the term edit page

      :param string $hook_suffix: what page we're running this on.

   .. php:method:: Largo_Term_Sidebars::edit_terms()

      Save the results from the term edit page

      :filter: edit_terms
      :param int|string $term_id: the term ID.
      :param string $taxonomy: the taxonomy of the term.