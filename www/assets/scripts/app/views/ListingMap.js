/**
 * This is the view that manages the "Map" view of the listings search page.  Should control all of the rendering
 * zooming and such for the map.
 * @type {*}
 */

ListingMapView = BaseView.extend({
    id: 'listings-map',
    template_name: 'listings-map',
    map: undefined,
    markers: [],
    fetching: false,
    listing: undefined,

    events: {
        'click button.details': 'details',
        'click button.reserve': 'reserve'
    },

    initialize: function (options) {
        BaseView.prototype.initialize.call(this, options);
        this.parent = this.options.parent;
        options.parent.on('listingFetchComplete', this.updateLocations, this);
    },

    renderMap: function (location) {
        var me = this;
        var canvas = this.$el.find('#map-canvas');
        canvas.height(800);

        this.infoWindow = new google.maps.InfoWindow({content: 'Loading...', maxWidth: 500});
        /* Create the map */
        this.map = new google.maps.Map(canvas.get(0), {
            center: new google.maps.LatLng(location.latitude, location.longitude), /** Default to Houston if no location is chosen */
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoom: this.getZoom(location.range)
        });

        this.map.addListener('bounds_changed', function () {
            me.fetchLocations();
        });
    },

    fetchLocations: function () {
        var me = this;

        if (this.fetching) {
            return false;
        }

        this.fetching = true;


        console.log(me.map.getCenter().lat());
        this.parent.fetch_xhr = this.parent.listingListCollection.fetch({
            data: {
                start: 0,
                limit: 9999,
                location: {
                    latitude: me.map.getCenter().lat(),
                    longitude: me.map.getCenter().lng(),
                    range: me.getDistance()
                },
                listings_type: app.listingRouter.listings_type,
                bedrooms: app.listingRouter.chosen_bedrooms,
                amenities: JSON.stringify(app.listingRouter.chosen_amenities)
            },
            success: function (collection, response, options) {
                me.parent.trigger('listingFetchComplete');

                me.parent.decorateTitle(collection, 'near');
                me.fetching = false;
            }
        });
    },

    /**
     * When the REST call returns with the list of locations, we need to repaint the map and recenter it if
     * necessary.  Tries to get the correct zoom based on the user's range.
     */
    updateLocations: function () {
        var collection = this.options.parent.listingListCollection;
        var me = this;

        // Render the listings on the side of the map
        me.listingListView = new ListingListView({
            parent: this.parent
        });
        app.listingRouter._renderCollectionView(me.listingListView, collection, me.$el.find('#map-listings'));

        _.each(this.markers, function (marker) {
            marker.setMap(null);
        });

        collection.each(function (location) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(location.get('latitude'), location.get('longitude')),
                map: me.map,
                title: location.get('name'),
                location_id: location.get('uuid')
            });

            me.markers.push(marker);

            google.maps.event.addListener(marker, 'click', function () {
                me.locationDetails(marker);
            });
        });
    },

    locationDetails: function (marker) {
        var me = this;
        if (!marker.content) {
            var listing = new Listing({
                uuid: marker.location_id
            });
            listing.fetch({
                success: function (model) {
                    marker.content = _.template(app.template_cache.get('listing-map-popup'), model.toJSON());
                    me.infoWindow.setContent(marker.content);
                    me.infoWindow.open(me.map, marker);
                    me.listing = listing;
                }
            });
        } else {
            me.infoWindow.setContent(marker.content);
            me.infoWindow.open(me.map, marker);
        }
    },

    reserve: function (event) {
        var me = this;
        /* Kill the old view */
        if (me.modalView) {
            me.modalView.remove();
        }

        /* Create a new view */
        me.reserveView = new ReserveView({
            model: me.listing
        });
        me.modalView = new Backbone.BootstrapModal({
            content: me.reserveView,
            modalClass: 'reserve',
            allowOk: false,
            okText: 'SUBMIT',
            okCloses: false
        }).open();
        return false;
    },

    details: function (event) {
        var url = $(event.currentTarget).attr('data-url');
        app.listingRouter.navigate(app.listingRouter.root_url + 'listing/' + url, true);
        return false;
    },

    getDistance: function () {
        var bounds = this.map.getBounds();

        var center = bounds.getCenter();
        var ne = bounds.getNorthEast();

        // r = radius of the earth in statute miles
        var r = 3963.0;

        // Convert lat or lng from decimal degrees into radians (divide by 57.2958)
        var lat1 = center.lat() / 57.2958;
        var lon1 = center.lng() / 57.2958;
        var lat2 = ne.lat() / 57.2958;
        var lon2 = ne.lng() / 57.2958;

        // distance = circle radius from center to Northeast corner of bounds
        var dis = r * Math.acos(Math.sin(lat1) * Math.sin(lat2) +
            Math.cos(lat1) * Math.cos(lat2) * Math.cos(lon2 - lon1));

        return dis;
    },

    getZoom: function (range) {
        if (range == 100) {
            return 6;
        } else if (range == 75) {
            return 8;
        } else if (range == 50) {
            return 9;
        } else if (range == 25) {
            return 10;
        } else {
            return 12;
        }
    }
});