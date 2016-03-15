ListingListCollection = BaseListCollection.extend({
    model: Listing,
    total_count : 0,

    url: function() {
        return app.rest_root + 'listings/';
    },

    parse : function(response) {

        /* Set the location on the listingRouter */
        app.listingRouter.location = response.location;
        app.listingRouter.amenity_counts = response.amenity_counts;
        app.listingRouter.range = response.range;
        app.listingRouter.url_data.range = response.range;
        /* Pull the total count off of the response */
        this.total_count = response.count;
        this.page = response.page;

        return response.listings;
    }
});