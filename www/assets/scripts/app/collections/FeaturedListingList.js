FeaturedListingListCollection = BaseListCollection.extend({
    model: Listing,
    url: function() {
        return app.rest_root + 'listings/featured';
    }
});