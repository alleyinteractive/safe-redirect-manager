<div id="boilerplate">
	<div class="row-fluid clearfix">
        <ul id="footer-social" class="social-icons">
            <?php
            /**
             * Default behavior for Largo is to include social links in the footer (not toggleable)
             * Removing for now, explicitly
             * TODO: Make toggle in admin to turn on/off
             */
                //largo_social_links();
            ?>
        </ul>
        <div class="footer-bottom clearfix">

            <!-- If you enjoy this theme and use it on a production site we would appreciate it if you would leave the credit in place. Thanks :) -->
            <span class="footer-credit <?php echo ( !INN_MEMBER ? 'footer-credit-padding-inn-logo-missing' : ''); ?>">
                <br />
                <?php
                printf( __('Built with the <a href="%s">Largo WordPress Theme</a> from the <a href="%s">Institute for Nonprofit News</a>.', 'largo'),
                    'https://largo.inn.org/',
                    'https://inn.org'
                );
                do_action('largo_after_footer_copyright');
                largo_nav_menu(
                    array(
                        'theme_location' => 'footer-bottom',
                        'container' => false,
                        'depth' => 1
                    ) );
                ?>
            </span>
            <span class="footer-copyright">
                <?php largo_copyright_message(); ?>

            </span>
            <div id="mc_embed_signup">
                <form action="https://onsomething.us13.list-manage.com/subscribe/post?u=2d4d8830ab1f09b0b01a0241a&amp;id=744ed54983" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                        <h2>Feed your curiosity between episodes</h2>
                        <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
                        <div class="mc-field-group">
                            <label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
                            </label>
                            <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email Address">
                        </div>
                        <div class="mc-field-group">
                            <input type="hidden" value="footer" name="FORMUSED" class="" id="mce-FORMUSED">
                        </div>
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_2d4d8830ab1f09b0b01a0241a_744ed54983" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                    </div>
                </form>
            </div>
        </div>



        <!--End mc_embed_signup-->

	</div>

	<p class="back-to-top visuallyhidden"><a href="#top"><?php _e('Back to top', 'largo'); ?> &uarr;</a></p>
</div>
