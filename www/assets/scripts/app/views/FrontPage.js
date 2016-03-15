FrontPageView = Backbone.View.extend({
    /** No events -- no el available in this view **/

    render: function () {
        /* Load the featured Listings */
        this.featuredListings = new FeaturedListingListCollection();

        this.featuredListings.fetch({
            data: {slider: true},
            success: function (collection, response, options) {
                var attributes = {};
                attributes.listings = collection.toJSON();
                var html = _.template(app.template_cache.get('listings-featured'), attributes);
                $("#featured-listings").html(html);
            }
        })

     this.comboListings = new ComboListingListCollection();

        this.comboListings.fetch({
            data: {slider: true},
            success: function (collection, response, options) {
                var attributes = {};
                attributes.cities = collection.toJSON();
                var html = _.template(app.template_cache.get('combo-list'), attributes);
                $("#combo-listings").html(html);
            }
        });

            

        //$("#destinations-pager").hide();
        this.popularCities = new PopularCityListCollection();

        this.popularCities.fetch({
            data: {slider: true},
            success: function (collection, response, options) {
                var attributes = {};
                attributes.cities = collection.toJSON();
                var html = _.template(app.template_cache.get('cities-popular'), attributes);
                $("#popular-destinations").html(html);
            }
        });

        return this;
    }

});