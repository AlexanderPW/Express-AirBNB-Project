SubListingsSidebarView = BaseView.extend({
    id: 'listings-sidebar',
    className: 'listings-sidebar',
    template_name: 'sublistings-sidebar',

    ADDRESS_COMPONENT_STATE: 'administrative_area_level_1',
    ADDRESS_COMPONENT_CITY: 'locality',

    events: {
        'change .amenities input[type="checkbox"]' : 'change_amenities',
        'change .bedrooms input[type="radio"]' : 'change_bedrooms',
        'change .listings-type input[type="radio"]' : 'change_listings_type',
        'click .see-more' : 'show_amenities',
        'click a.map-view' : 'map_view',
        'click a.list-view' : 'list_view'
    },

    initialize: function (options) {
        BaseView.prototype.initialize.call(this, options);

        options.parent.on('listingFetchComplete', this.update_sidebar_from_results, this);
    },

    render: function () {
        BaseView.prototype.render.call(this);

        if(app.sublistingRouter.url_data && app.sublistingRouter.url_data.zipcode){
            this.$el.find('#cityzip').val(app.sublistingRouter.url_data.zipcode);
        }
        if(app.sublistingRouter.url_data && app.sublistingRouter.url_data.city){
            this.$el.find('#cityzip').val(decodeURIComponent(app.sublistingRouter.url_data.city));
        }
        if(app.sublistingRouter.url_data && app.sublistingRouter.url_data.range){
            this.$el.find('#range').val(app.sublistingRouter.url_data.range);
        }
        if(app.sublistingRouter.url_data && app.sublistingRouter.url_data.state){
            this.$el.find('#state').val(app.sublistingRouter.url_data.state);
        }
        if(app.sublistingRouter.url_data && app.sublistingRouter.url_data.mode) {
            this.$el.find('#mode').val(app.sublistingRouter.url_data.mode);
        }

        if(app.sublistingRouter.listings_type=='featured') {
            this.$el.find('#listings-featured').attr('checked', 'checked');
        } else {
            this.$el.find('#listings-all').attr('checked', 'checked');
        }

        /** Show the correct image in the top left of the side bar for switching views */
        if(app.sublistingRouter.url_data['mode']===app.sublistingRouter.LISTING_MODE_MAP) {
            this.$el.find('a.list-view').removeClass('active');
            this.$el.find('a.map-view').addClass('active');
        } else {
            this.$el.find('a.list-view').addClass('active');
            this.$el.find('a.map-view').removeClass('active');
        }
        return this;
    },

    /**
     * If the user hasn't entered a state, but instead a city or zipcode, we try to fill in the state
     * with what we get back from geocoding
     */
    update_sidebar_from_results : function() {
        var me = this;
        if(app.sublistingRouter.location) {
            if(!this.$el.find('#state').val()) {
                var address_components = app.sublistingRouter.location.address_components;
                _.each(address_components, function(address_component) {
                    if(_.contains(address_component.types, me.ADDRESS_COMPONENT_STATE) ) {
                        me.$el.find('#state').val(address_component.short_name);
                    }
                });
            }

            if(!this.$el.find('#cityzip').val()) {
                var address_components = app.sublistingRouter.location.address_components;
                _.each(address_components, function(address_component) {
                    if(_.contains(address_component.types, me.ADDRESS_COMPONENT_CITY) ) {
                        me.$el.find('#cityzip').val(address_component.short_name);
                    }
                });
            }
        }

        me.$el.find('#range').val(app.sublistingRouter.range);

        if(app.sublistingRouter.amenity_counts.length>0) {
            _.each(app.sublistingRouter.amenity_counts, function(amenity_count) {
                me.$el.find('label#amenity-'+amenity_count.id+' em').text('('+amenity_count.cnt+')');
            });
        }
    },

    /**
     * Fired when the user clicks on one of the checkboxes next to the amenities.  Fires the filterUpdate event
     * which reloads the listings
     * @param event
     * @returns {boolean}
     */
    change_amenities : function(event) {
        app.sublistingRouter.chosen_amenities = [];
        this.$el.find('.amenities input[type="checkbox"]:checked').each(function(checkbox) {
            app.sublistingRouter.chosen_amenities.push($(this).val());
        });

        this.trigger('filterUpdate');
        return false;
    },

    /**
     * Fired when the user clicks on the bedroom number.  Fires an filterUpdate event which reloads the listings
     * @param event
     * @returns {boolean}
     */
    change_bedrooms : function(event) {
        app.sublistingRouter.chosen_bedrooms = this.$el.find('.bedrooms input[type="radio"]:checked').val();

        this.trigger('filterUpdate');
        return false;
    },

    /**
     * Fired when the user clicks on the all/featured toggle.  Fires an filterUpdate event which reloads the listings
     * @param event
     * @returns {boolean}
     */
    change_listings_type : function(event) {
        app.sublistingRouter.listings_type = this.$el.find('.listings-type input[type="radio"]:checked').val();

        this.trigger('filterUpdate');
        return false;
    },

    /**
     * When the user clicks the "See More Amenities..." button on the sidebar, we show the full list of amenities
     * @param event
     * @returns {boolean}
     */
    show_amenities : function(event) {
        this.$el.find('.amenities .more').show();
        $(event.currentTarget).hide();
        return false;
    },

    map_view : function(event) {
        $(event.currentTarget).addClass('active');
        this.$el.find('.list-view').removeClass('active');

        this.options.parent.switchView(app.sublistingRouter.LISTING_MODE_MAP);
        this.$el.find('#mode').val(app.sublistingRouter.listing_mode);
        return false;
    },

    list_view : function(event) {
        $(event.currentTarget).addClass('active');
        this.$el.find('.map-view').removeClass('active');

        this.options.parent.switchView(app.sublistingRouter.LISTING_MODE_LIST);
        this.$el.find('#mode').val(app.sublistingRouter.listing_mode);
        return false;
    }
});