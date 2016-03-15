SubListingsView = BaseView.extend({
    id: 'listings',
    className: 'submarkets',
    template_name: 'submarkets',
    ADDRESS_COMPONENT_CITY: 'locality',
    ADDRESS_COMPONENT_STATE: 'administrative_area_level_1',

    LISTING_START: 0,
    LISTING_LIMIT: 5,

    fetch_xhr : undefined,

    render: function () {
        BaseView.prototype.render.call(this);

        this.sublistingsSidebarView = new SubListingsSidebarView({
            parent : this
        });

        app.sublistingRouter._renderView(this.sublistingsSidebarView, this.$el.find('.listings-sidebar'));

        if (!this.listingListCollection) {
            this.listingListCollection = new SubListingListCollection([], {
                view: this.sublistingListView
            });
        }
        this.sublistingsSidebarView.on('filterUpdate', function() {
            this.LISTING_START = 0;
            this.renderResults();
        }, this);
        this.renderResults();
        return this;
    },

    /**
     * Switch the view between the map and list view
     */
    switchView : function(listing_mode) {
        app.sublistingRouter.url_data['mode']  = listing_mode;

        /* We need to update the url so that we mark that we are in map mode */
        var url = 'submarkets';

        if(app.sublistingRouter.url_data['popular']) {
            url+="/"+app.sublistingRouter.LISTING_MODE_FEATURED;
        }
        _.each(app.sublistingRouter.url_data_keys, function(key, iterator) {
            var value = app.sublistingRouter.url_data[key];
            if(value) {
                url+="/"+value;
            }
        });

        app.sublistingRouter.navigate(url, {replace: true});

        if(this.listingListView) {
            this.listingListView.close();
            this.listingPagerView.close();
        }
        if(this.listingMapView) {
            this.listingMapView.close();
            this.listingMapView = undefined;
        }
        this.renderResults();
    },

    /**
     * Render either the list of listings or the map
     */
    renderResults : function() {
        if(!app.sublistingRouter.url_data['mode'] || app.sublistingRouter.url_data['mode']===app.sublistingRouter.LISTING_MODE_LIST) {
            this.renderList();
        } else {
            this.renderMap();
        }
    },

    /**
     * Called to render the actual list of listings.  This is called every time someone changes one of the filters
     */
    renderList : function() {
        if(this.listingListView) {
            this.listingListView.close();
            this.listingPagerView.close();
        }

        $(".listings-list").mask("Loading Results...", 0, 200);
        var newtext = $('#cityzip').val();

        var me = this;
        this.listingListView = new ListingListView({
            parent : this
        });

        /** Abort any requests that are still running */
        if(this.fetch_xhr && this.fetch_xhr.readyState > 0 && this.fetch_xhr.readyState < 4){
            this.fetch_xhr.abort();
        }
        this.fetch_xhr = this.listingListCollection.fetch({
            data : {
                subcity:'blahblah',
                start: this.LISTING_START,
                limit: this.LISTING_LIMIT,
                location : JSON.stringify(app.sublistingRouter.url_data),
                bedrooms : app.sublistingRouter.chosen_bedrooms,
                listings_type: app.sublistingRouter.listings_type,
                amenities : JSON.stringify(app.sublistingRouter.chosen_amenities)
            },
            success: function (collection, response, options) {
                app.sublistingRouter._renderCollectionView(me.listingListView, collection, me.$el.find('.listings-list'));

                me.listingPagerView = new ListingPagerView({
                    collection: collection,
                    parent: me
                });
                me.listingPagerView.on('pageUpdate', me.renderResults, me);

                app.listingRouter._renderView(me.listingPagerView, me.$el.find('.listings-pager'));

                me.decorateTitle(collection, 'near');

                me.trigger('listingFetchComplete');
            }
        });
    },

    renderMap : function() {
        var me = this;
        if(!this.listingMapView) {
            this.listingMapView = new ListingMapView({
                parent : this
            });

            app.sublistingRouter._renderView(this.listingMapView, this.$el.find('.listings-list'), function() {
                $.getJSON(app.rest_root + 'submarkets/location', {
                    location: JSON.stringify(app.sublistingRouter.url_data)
                }, function(response) {
                    me.listingMapView.renderMap(response);
                });
            });
        }

        /** Abort any requests that are still running */
        if(this.fetch_xhr && this.fetch_xhr.readyState > 0 && this.fetch_xhr.readyState < 4){
            this.fetch_xhr.abort();
        }
    },

 
    decorateTitle : function(collection, locationText) {
        var me = this;

    var newtext = $('#cityzip').val();
     var trimnewtxt = $.trim(newtext).replace(' ', '%20'); 

    var cityholder = $('#submarket-title');
     var newstate = $('#state').val();
             
        var text = collection.total_count + ' Communities found';
        if(app.sublistingRouter.location) {
            text+=' ' + locationText + ' ';
            var address_components = app.sublistingRouter.location.address_components;
            _.each(address_components, function(address_component) {
                if(_.contains(address_component.types, me.ADDRESS_COMPONENT_CITY) ) {
                    text+=newtext;
                } else if(_.contains(address_component.types, me.ADDRESS_COMPONENT_STATE) ) {
                   
                    text+=", "+address_component.short_name;
                   
                }
            });
        }
        this.$el.find('h3.title').text(text);
      // cityholder.html(trimnewtxt);

                jQuery.get('/app/rest/submarkets/get_major_city/'+trimnewtxt+'/'+newstate+'/', {}, function (cities) {
           _.each(cities, function(city) {
         cityholder.html('Submarket of '+city.city);
            });
        });
    }
});