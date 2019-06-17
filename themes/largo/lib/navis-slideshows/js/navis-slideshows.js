(function() {
    var $ = jQuery;
    var attrs = [];

    // Easy function to check if a user is logged in
    // That way we can easily hide/toggle the wpadminbar when
    // a slideshow image is opened.
    function is_user_logged_in(){
        if($('#wpadminbar').length){
            return true;
        }
    }

    // bit to store whether the sticky nav was visible
    var sticky_nav_was_visible = false;

    // the sticky nav selector
    var sticky_nav_selector = '.sticky-nav-holder';

    // If the Largo sticky nav is visible, hide the sticky nav,
    // and remember that it was visible
    function maybeHideStickyNav() {
        $nav = $(sticky_nav_selector);

        // Largo uses the 'show' clas rather than jQuery .show()/.hide()
        if ($nav.hasClass('show')) {
            $nav.removeClass('show').hide();
            sticky_nav_was_visible = true;
        }
    }

    // If the Largo sticky nav was once visible, unhide it
    // otherwise, don't unhide it.
    function maybeShowStickyNav() {
        if (sticky_nav_was_visible) {
            $(sticky_nav_selector).addClass('show').show();
            sticky_nav_was_visible = false;
        }
    }

    // prevent page scrolling while modal is open
    function bodyStopScroll() {
        $('html, body').css({
            overflow: 'hidden',
            height: '100%'
        });
    }

    // allow page scrolling again
    function bodyStartScroll() {
        $('html, body').css({
            overflow: 'auto',
            height: 'auto'
        });
    }

    function createSlider($gallery, slide_num){

        var post_id = $gallery.attr('id'),
            post_id_clean = post_id.replace('slides-', ''),
            slick_opts = {
                adaptiveHeight: true,
                lazyLoad: 'progressive',
                dots: true,
                prevArrow: '<a class="slick-previous slick-navigation" href="#" title="Previous">Previous</a>',
                nextArrow: '<a class="slick-next slick-navigation" href="#" title="Next">Next</a>',
                initialSlide: slide_num
            };

        // Make the slider
        $gallery.slick(slick_opts);

        // If the slider is full size, allow the user to click to advance
        if ($gallery.hasClass('navis-full')){
            $gallery.find('.slick-slide').click(function(){
                $('.slick-next').trigger('click');
            });
        }

        // Distinguish between click or drag (drag >100ms)
        var valid_click = true;
        var delay = 100;

        // Sets the flag to false
        var drag = function(){
            valid_click = false;
        }

        var $target = $($gallery.find('img'));

        // mousedown, a global variable is set to the timeout function
        $target.mousedown( function(){
            cancel_click = setTimeout( drag, delay );
        })

        // mouseup, the timeout for drag is cancelled
        $target.mouseup( function(e){
            clearTimeout( cancel_click );

            if(valid_click){
                var $slider = $(e.target.closest('.navis-slideshow'));
                expandSlider($slider);
            }

            valid_click = true;
        })
    }

    function createGallery($gallery, slide_num){

        // Prep each image in the gallery
        // When the parent is clicked, create a slider
        $gallery.find('img').each(function(){
            var $this = $(this);
            var src = $this.data('lazy');
            var $parent = $this.parent();

            $parent.prepend('<div style="background-image:url(' + src + ');"></div>');
            $parent.click(function(){
                var $this = $(this);
                if ($this.closest('.largo-img-grid').length > 0){
                    $gallery.addClass('navis-slideshow').removeClass('largo-img-grid').addClass('navis-full');
                    // The parent index lets the slider know which image to show first
                    createSlider($gallery, $parent.index());
                    addCloseX($gallery);
                }
            });
        });


        //$gallery.slick(slick_opts);
    }

    function addCloseX($slider){
        $slider.prepend('<span class="navis-before">X</span>');
        $('.navis-before').click(function(){
            closeFull($slider);
        });
    }

    // Full-width function for small slideshows
    function expandSlider($slider){
        var full = 'navis-full';

        maybeHideStickyNav();
        bodyStopScroll();

        if (!$slider.hasClass(full)){
            $slider.addClass(full);
            $slider.slick('setPosition');

            addCloseX($slider);
        }

        // Without the timeout, the full-width slider will advance beyond the first slide...
        // ...because it thinks the click to expand is also the click to advance.
        setTimeout(function(){
            $slider.find('.slick-slide').click(function(){
                $('.slick-next').trigger('click');
            });
        },1000);
    };

    function closeSingle(gallery, attrs){
        $('.navis-before').remove(); // Removes close (X) button

        if (gallery.hasClass('navis-single') && attrs){
            var img = gallery.find('img');

            attrs.forEach( function(attr, index, attrs) {
                if ( typeof attr === 'undefined' ) {
                    attrs[index] = null;
                }
            });
            // Reset attributes
            img.attr('sizes', attrs[0]);
            img.attr('width', attrs[1]);
            img.attr('height', attrs[2]);
            gallery.attr('style', attrs[3]);
        }

        $('.navis-single').removeClass('navis-full navis-slideshow navis-single'); // Removes navis classes

        bodyStartScroll();
        maybeShowStickyNav();
    }

    function closeFull($slider){
        $('.navis-before').remove(); // Removes close (X) button
        $('.navis-full').removeClass('navis-full');

        // Check if the slider belongs to a single image or a gallery/slideshow/grid (true is for gallery)
        if($slider.hasClass('slick-slider')){
            $slider.slick('setPosition');

            // Check if the slider belongs to a gallery or a grid (true is for grid)
            if (!$slider.hasClass('none-up')){
                $slider.slick('unslick');
                $slider.removeClass('navis-slideshow').addClass('largo-img-grid');


                // Reconfigure grid view
                var $parent = $slider.find('>div');
                $parent.click(function(){
                    var $this = $(this);
                    if ($this.closest('.largo-img-grid').length > 0){
                        $slider.addClass('navis-slideshow').removeClass('largo-img-grid').addClass('navis-full');
                        createSlider($slider, $this.index());
                        addCloseX($slider);
                    }

                });
            }
        }
        // Unbind the click event so that small slideshows don't break
        $slider.find('.slick-slide').unbind('click');

        // Return images to original sizes
        closeSingle($slider, attrs);
    }

    $(document).ready(function() {
        // For single images, loop through each image on the page
        $('article.post img').each(function() {

            // If this is not already a slideshow
            var img = $(this);
            if ( img[0].hasAttribute('src') ){

                // When an image is clicked, add classes to the parent element to open the lightbox view
                img.click(function(){
                    var gallery = img.parent();

                    bodyStopScroll();

                    // Hide floating social buttons when lightbox view is opened, since
                    // the stacking order allows the social buttons to be on top of the lightbox
                    $('#floating-social-buttons').hide();

                    // If our gallery parent `wp-block-columns` has the `alignfull` class,
                    // let's add our `no-transform` class so our full image works as expected.
                    if(gallery.parent('alignfull') ){
                        $(gallery).closest('.alignfull').addClass('no-transform');
                    }

                    // If a user is logged in, let's hide the admin menu.
                    // That way it doesn't overlap the image.
                    if(is_user_logged_in()){
                        $('#wpadminbar').hide();
                    }

                    maybeHideStickyNav();

                    // If the image isn't linked
                    if (gallery[0].localName !== "a"){
                        gallery.addClass('navis-slideshow navis-single navis-full');

                        // Add the close (X) button
                        gallery.prepend('<span class=\"navis-before\">X</span>');

                        // Save original attribute values, for use in closeSingle();
                        // order is important
                        attrs = [
                            img.attr('sizes'),
                            img.attr('width'),
                            img.attr('height'),
                            gallery.attr('style')
                        ];

                        // Adjust styles so images can expand to full width
                        gallery.css('max-width','100%');
                        img.removeAttr('width height sizes');

                        // Close the lightbox when the close (X) button is clicked
                        $('.navis-single .navis-before').click(function(){
                            closeSingle(gallery, attrs);

                            // If the gallery parent had `alignfull` class when we opened the lightbox,
                            // let's remove our `no-transform` supporter class
                            if(gallery.parent('alignfull')){
                                $(gallery).closest('.alignfull').removeClass('no-transform');
                            }

                            // If a user is logged in, we can go ahead and
                            // restore the admin menu when the lightbox is closed.
                            if(is_user_logged_in()){
                                $('#wpadminbar').show();
                            }

                            maybeShowStickyNav();

                        });
                    }
                });

            }
        });

        // For each gallery, convert to a grid if necessary
        $('.navis-slideshow').each(function() {
            var $gallery = $(this);
            var slide_num = 0;

            // 'none-up' is the slideshow setting (0 columns)
            if ($gallery.hasClass('none-up')){
                var post_id = $gallery.attr('id'),
                post_id_clean = post_id.replace('slides-', '');

                // Check for permalink
                if (window.location.hash) {
                    var search = new RegExp(post_id_clean, 'g');

                    if (search.test(window.location.hash.replace('#', ''))) {
                        var fragment = window.location.hash.replace(/^\#.*\//, '', 'gmi'),
                            slideNum = parseInt(fragment);

                        if (!isNaN(slideNum))
                            slide_num = slideNum - 1;
                    }
                }
                // Create a slider for the gallery
                createSlider($gallery, slide_num);
            } else {
                // If this gallery should have a grid layout, add the largo grid class
                $gallery.removeClass('navis-slideshow').addClass('largo-img-grid');
                createGallery($gallery, slide_num);
            }
        });
    });

    $(document).keyup(function(e) {
        var $slider = $('.navis-full');

        if (e.keyCode == 27) { // escape
            if ($slider.length > 0) {
                closeFull($slider);

                // If our slider's parent has `alignfull` class,
                // we'll want to remove our `no-transform` class to restore our transform properties
                // so `alignfull` looks correct
                if($slider.parent('alignfull')){
                    $slider.closest('.alignfull').removeClass('no-transform');
                }

                // If a user is logged in, we can go ahead and
                // restore the admin menu when the lightbox is closed.
                if(is_user_logged_in()){
                    $('#wpadminbar').show();
                }

            }
        }
    });
})();
