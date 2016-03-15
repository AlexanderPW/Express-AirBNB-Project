var ListingRouter = BaseRouter.extend({
    container: '#backbone-container',
    listing_list_container : 'listing-list-container',
    listing_details_container : 'listing-details-container',
    root_url: app.root_url,

    /**
     * The location data pulled from the current url.  We use this to put together an address and range
     * to send to google to fetch a lat/lng result and then use that to fetch locations that are within the range.
     * URL structure is listings/US/TX/Austin/78704/50/map
     *
     * Values:
     * ['country', 'state', 'city', 'zipcode', 'range', 'mode']
     */
    url_data_keys : ['country', 'state', 'city', 'zipcode', 'range', 'mode'],
    url_data : {},

    /**
     * In the sidebar of the listing search, a user is allowed to specify which amenities they want.  This tracks
     * the chosen array of amenities
     */
    chosen_amenities : [],

    /**
     * In the sidebar of the listing search, a user can choose how many bedrooms they want.
     */
    chosen_bedrooms : 0,
    /**
     * When the address from the url data is sent to google, google sents back an object which represents the location
     * We use that to populate things on the search
     */
    location: undefined,

    /**
     * Whether to show the listings as a list or as a map
     */
    listing_mode : 'list',
    LISTING_MODE_LIST : 'list',
    LISTING_MODE_MAP : 'map',
    LISTING_MODE_FEATURED: 'Featured',


    /**
     * The counts of how many of each of the amenities are in the search results
     */
    amenity_counts : [],

    range : 10,

    routes: {
        'submarkets': 'listings',
        'submarkets/': 'listings',

       
        'listing': 'listing_details',
        'listing/*path': 'listing_details',

        'listings/*path': 'listings',

        '*path' : 'front_page'
    },

    _get_user: function (callback) {
        if (!this.user) {
            this.user = new User();
            this.user.fetch({
                success: callback
            })
        } else {
            callback();
        }
    },

    _close_views : function() {
        /* Kill the old view */
        if (this.listingView) {
            this.listingView.close();
        }
        if (this.listingsView) {
            this.listingsView.close();
        }
    },

    _init_listing_views : function() {
        if($('#'+this.listing_list_container).length==0) {
            $(this.container).append('<div id="'+this.listing_list_container+'"></div>');
        }
        if($('#'+this.listing_details_container).length==0) {
            $(this.container).append('<div id="'+this.listing_details_container+'"></div>');
        }
    },

    _process_url : function() {
        var me = this;
        var fragments = this._get_fragments();

        /** Rip out 'Featured' if it is in the beginning of the fragments **/
        if(fragments[0]===me.LISTING_MODE_FEATURED) {
            me.url_data['popular'] = true;
            fragments = _.rest(fragments, 1);
        } else {
            me.url_data['popular'] = false;
        }

        _.each(fragments, function(fragment, index) {
            var key = me.url_data_keys[index];

            if(key=='zipcode' && (parseInt(fragment)<=100)) {
                me.url_data['range'] = fragment;
            } else if(key=='range' && (fragment=="list" || fragment=="map")) {
                me.url_data['mode'] = fragment;
            } else {
                me.url_data[key] = fragment;
            }
        });
    },

    /**
     * Get the list of fragments from the url based on splitting it by "/"
     * @private
     */
    _get_fragments : function() {
        var url = Backbone.history.fragment.replace('listings', '');
        var fragments = url.split('/');

        /* Filter out the empty values */
        fragments = _.filter(fragments, function(val) {
            return val!="";
        });
        return fragments;
    },

    front_page : function() {
        if(!Backbone.history.fragment) {
            this.frontPageView = new FrontPageView();
            this.frontPageView.render();
        }
    },

    /**
     * Loads the dashboard and the list of events
     */
    listings: function () {
        //this._close_views();
        this._init_listing_views();
        $('#'+this.listing_details_container).hide();

        if(this.listingsView) {
            $('#'+this.listing_list_container).show();
        } else {
            this._process_url();

            this.listingsView = new ListingsView();
            this._renderView(this.listingsView, '#'+this.listing_list_container);
        }
    },

    listing_details: function () {
        var me = this;
        var fragments = this._get_fragments();
        /** The listing name url is the last fragment **/
        var listingUrl = fragments[fragments.length-1];

        //this._close_views();
        this._init_listing_views();
        $('#'+this.listing_list_container).hide();

        /* Create a new view */
        this.listingView = new ListingView({
            model: new Listing({
                uuid: listingUrl
            })
        });
        /* Fetch calls the render */
        this.listingView.model.fetch({
            success: function (model, response, options) {
                me._renderView(me.listingView, '#'+me.listing_details_container, function() {
                    me.listingView.after_render();
                });
            }
        });
    }
});

app.listingRouter = new ListingRouter;
jQuery(document).ready(function () {
    $ = jQuery.noConflict();

    app.listingRouter.start();
});