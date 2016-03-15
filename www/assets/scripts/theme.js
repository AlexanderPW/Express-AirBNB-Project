app.theme = {

    /** Pops up the get rates/submit inquiry modal **/
    reserve_modal : function() {
        var me = this;
        /* Kill the old view */
        if (me.modalView) {
            me.modalView.remove();
        }
        var listing = new Listing();

        if(app.listingRouter && app.listingRouter.listingView) {
            app.listingRouter.listingView.reserve();
        } else {
            /* Create a new view with a default model */
            me.reserveView = new ReserveView({
                model: listing
            });
            me.modalView = new Backbone.BootstrapModal({
                content: me.reserveView,
                modalClass: 'reserve',
                allowOk: false,
                okText: 'SUBMIT',
                okCloses: false
            }).open();

        }
        return false;
    },

    /** Load the list of featured cities in the footer */
    load_footer_featured_cities : function() {
        jQuery.get('/app/rest/cities/popular/', {}, function (cities) {
            var menu = $('#menu-footer-featured-menu2');
            _.each(cities, function(city) {
                menu.append('<li><a href="/listings/Featured/'+city.url+'">'+city.city+','+city.state+'</li>');
            });
        });
    },

    load_landing_featured_cities : function() {
        jQuery.get('/app/rest/cities/popular/', {}, function (cities) {
            var menu2 = $('#featured-cities-landing');
            _.each(cities, function(city) {
                menu2.append('<div class="col-lg-4"><div class="listing city" data-url="/listings/Featured/'+city.url+'" style="background-image:url('+city.photo+')"><h5><a href="/listings/Featured/'+city.url+'">'+city.city+'</a></h5></div></div>');
            });
        });
    },

 load_submarket_cities : function() {
        var data = jQuery('#submarkets').attr('data-id');
        jQuery.get('/app/rest/submarkets/get_city_for_sub/'+data+'/', {}, function (cities) {
            var menu2 = $('#submarkets');
            _.each(cities, function(city) {
                menu2.css("display", "block");
                menu2.append('<a href="/submarkets/Featured/US/'+city.state+'/'+city.city+'">'+city.city+'</a>');
            });
        });
    },
load_submarket_title : function() {
    var titlevalue = 'blahblah';
    var title = jQuery('#majorcity');
    title.append(' blahblah'+titlevalue);
},

// THIS MAY NOT BE COMPLIANT!!  

hide_chatlogo : function() {
    jQuery( '.footer-company-logo-sec' ).each(function () {
    this.style.setProperty( 'display', 'none', 'important' );
});
},

 

    /**
     * Opens the "Contact Us" modal on the home page and from the footer of the site -- pulls the form from wordpress
     */
    load_apply_job_modal: function (title) {
        var me = this;

        if (jQuery("#apply-job-modal").length > 0) {
            jQuery("#apply-job-modal").modal('show');
            jQuery("#input_2_7").val(title);
        } else {

            jQuery.get('/about/careers/apply-popup-window/', {}, function (data) {
                jQuery('body').append(data);
                jQuery("#apply-job-modal").modal('show');
                me.gravity_placeholders();
                jQuery("#input_2_7").val(title);
            });
        }
    },

    gravity_placeholders: function () {
        jQuery('.gform_fields li').each(function () {
            if(jQuery(this).hasClass('no-placeholder') || jQuery(this).find('.ginput_complex').length>0) {
                return;
            } else {
                var label = jQuery(this).find('label').text();
                jQuery(this).find('input').attr('placeholder', label);
                jQuery(this).find('textarea').attr('placeholder', label);
                jQuery(this).find('select').attr('placeholder', label);
                jQuery(this).find('label').addClass('sr-only');
            }
        });

        jQuery('.gform_fields li .ginput_complex span').each(function () {
            var label = jQuery(this).find('label').text();
            jQuery(this).find('input').attr('placeholder', label);
            jQuery(this).find('label').addClass('sr-only');
        });
    }
}

jQuery(document).ready(function() {
    app.theme.load_footer_featured_cities();

    app.theme.load_landing_featured_cities();

    app.theme.load_submarket_cities();

    app.theme.load_submarket_title();

    app.theme.hide_chatlogo();

    jQuery("button.apply").click(function() {
        /* Do something with the apply button */
        return false;
    });

    jQuery('#main-search').validate();

    jQuery('#featured-listings').on('click', '.listing.item', function() {
        var url = jQuery(this).attr('data-url');
        window.location.href = url;
    });

    jQuery('#featured-listings').on('click', '.listing.city', function() {
        var url = jQuery(this).attr('data-url');
        window.location.href = url;
    });

    jQuery('.btn-reservation').click(function() {
        app.theme.reserve_modal();
        return false;
    });
    jQuery('#general-reserve-form').ajaxForm({
        success: function(responseText, statusText, xhr, form) {
            app.success_message('Your reservation has been received successfully.  We will get back to you shortly.', $('#general-reserve-modal .alert-container'));
        }
    });

    jQuery("#button-contact-header").click(function() {
        var address = encodeURI('12777 Jones Rd STE 275, Houston, Texas 77070');
        window.open('https://maps.google.com/maps?daddr='+address,"","width=800,height=600");
    });

    jQuery("#career-accordion button.apply").click(function(event) {
        var title = $(event.currentTarget).attr('data-title');
        app.theme.load_apply_job_modal(title);
        return false;
    });

    jQuery(document).on('blur', 'input, textarea', function() {
        if(!$(this).val().trim()) {
            $(this).val('');
        }
    });

    jQuery(document).on('change', '.type-toggle select', function() {
        window.location = jQuery(this).val();
    });
})