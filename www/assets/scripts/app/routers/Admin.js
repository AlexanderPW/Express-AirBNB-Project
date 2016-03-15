var AdminRouter = BaseRouter.extend({
    root_url: app.admin_root,
    list_container: '#list-container',
    details_container: '#details-container',

    routes: {
        'listings': 'listings',
        'listings/': 'listings',

        'listing': 'listing',
        'listing/:id': 'listing',

        'cities': 'cities',
        'cities/': 'cities',

        'city': 'city',
        'city/:id': 'city',

        'sub': 'sub',
        'sub/:id': 'sub',

        'subedit': 'subedit',
        'subedit/:id': 'subedit',

        '*path': 'listings'
    },

    /**
     * Private function that is called to initiate the dashboard as a whole
     * @private
     */
    _admin: function (page_title, active_class, btn_text, btn_callback) {
        /* Load the templates up into the template cache and then kick off the main view */
        if (!this.adminView) {
            this.adminView = new AdminView({ });
            this.adminView.render();
        }
        this.adminView.set_page(page_title, active_class, btn_text, btn_callback);
        this.adminView.set_btn(btn_text, btn_callback);
    },

    /**
     * Loads the list of apartments
     */
    listings: function () {
        var me = this;
        $(this.details_container).hide();

        if (me.cityDataTableView) {
            me.cityDataTableView.remove();
            delete me.cityDataTableView;
        }

        if (!me.listingDataTableView) {
            app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
                me._admin('Listings', 'listings', 'Add Listing', function (event) {
                    app.adminRouter.navigate('listing', true);
                });

                /* Kill the old view */
                if (me.listingDataTableView) {
                    me.listingDataTableView.remove();
                }

                /* Create a new view */
                me.listingDataTableView = new ListingDataTableView({
                    url: app.rest_root + 'admin/listings',
                    edit_url: 'listing/',
                    columns: [
                        {
                            "title": "Property",
                            "mDataProp": "name"
                        },
                        {
                            "title": "Address",
                            "mDataProp": "address"
                        },
                        {
                            "title": "City",
                            "mDataProp": "city"
                        },
                        {
                            "title": "State",
                            "mDataProp": "state"
                        },
                        {
                            "title": "Featured",
                            "mDataProp": "featured"
                        },
                        {
                            "title": "Published",
                            "mDataProp": "published"
                        }
                    ]
                });
                /* Fetch calls the render */
                me._renderView(me.listingDataTableView, me.list_container);
            });
        } else {
            $(this.list_container).show();
            this.adminView.set_btn('Add Listing', function (event) {
                app.adminRouter.navigate('listing', true);
            });
        }
    },

    listing: function (listingId) {
        var me = this;
        $(this.list_container).hide();

        app.template_cache.load(['admin/admin', 'admin/listing'], function () {
            me._admin('View Listing', 'listings');

            /* Kill the old view */
            if (me.listingView) {
                me.listingView.remove();
            }

            /* Create a new view */
            me.listingView = new ListingView({
                model: new Listing({
                    urlRoot: app.rest_root + 'admin/listings/listing',
                    uuid: listingId
                })
            });
            /* Fetch calls the render */
            me.listingView.model.fetch({
                success: function (model, response, options) {
                    me._renderView(me.listingView, me.details_container);
                }
            });
        });
    },

    /**
     * Loads the list of cities
     */
    cities: function () {
        var me = this;
        $(this.details_container).hide();

        if (me.listingDataTableView) {
            me.listingDataTableView.remove();
            delete me.listingDataTableView;
        }

        if (!me.cityDataTableView) {
            app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
                me._admin('Cities', 'cities', 'Add City', function (event) {
                    app.adminRouter.navigate('city', true);
                });

                /* Kill the old view */
                if (me.cityDataTableView) {
                    me.cityDataTableView.remove();
                }

                /* Create a new view */
                me.cityDataTableView = new DataTableView({
                    url: app.rest_root + 'admin/cities',
                    edit_url: 'city/',
                    columns: [
                        {
                            "title": "City",
                            "mDataProp": "city"
                        },
                        {
                            "title": "State",
                            "mDataProp": "state"
                        },
                        {
                            "title": "Show City?",
                            "mDataProp": "is_popular"
                        },
                        {
                            "title": "# of Listings",
                            "mDataProp": "listing_count"
                        }
                    ]
                });
                /* Fetch calls the render */
                me._renderView(me.cityDataTableView, me.list_container);
            });
        } else {
            $(this.list_container).show();
            this.adminView.set_btn('Add City', function (event) {
                app.adminRouter.navigate('city', true);
            });
        }
    },

    city: function (cityId) {
        var me = this;
        $(this.list_container).hide();

        app.template_cache.load(['admin/admin', 'admin/city'], function () {
            me._admin('View city', 'cities');

            /* Kill the old view */
            if (me.cityView) {
                me.cityView.remove();
            }

            /* Create a new view */
            me.cityView = new CityView({
                model: new City({
                    urlRoot: app.rest_root + 'admin/cities/city',
                    uuid: cityId
                })
            });
            /* Fetch calls the render */
            me.cityView.model.fetch({
                success: function (model, response, options) {
                    me._renderView(me.cityView, me.details_container);
                }
            });
        });
    },

     sub: function (cityId) {
        var me = this;
        $(this.list_container).hide();

        app.template_cache.load(['admin/admin', 'admin/submarket'], function () {
            me._admin('Submarkets', 'submarket');

            /* Kill the old view */
            if (me.cityView) {
                me.cityView.remove();
            }

            /* Create a new view */
            me.subView = new SubmarketView({
                model: new City({
                    urlRoot: app.rest_root + 'admin/cities/sub',
                    uuid: cityId
                })
            });
            /* Fetch calls the render */
            me.subView.model.fetch({
                success: function (model, response, options) {
                    me._renderView(me.subView, me.details_container);
                }
            });
        });
    },
      subedit: function (subId) {
        var me = this;
        $(this.list_container).hide();

        app.template_cache.load(['admin/admin', 'admin/submarket'], function () {
            me._admin('Submarkets', 'submarket');

            /* Kill the old view */
            if (me.cityView) {
                me.cityView.remove();
            }

            /* Create a new view */
            me.subView = new SubmarketView({
                model: new City({
                    urlRoot: app.rest_root + 'admin/cities/editsub',
                    uuid: subId
                })
            });
            /* Fetch calls the render */
            me.subView.model.fetch({
                success: function (model, response, options) {
                    me._renderView(me.subView, me.details_container);
                }
            });
        });
    },
});

app.adminRouter = new AdminRouter;
$(document).ready(function () {
    app.adminRouter.start('/app/admin/');
});