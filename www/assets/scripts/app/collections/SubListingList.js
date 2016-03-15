SubListingListCollection = BaseListCollection.extend({
    model: Listing,
    total_count : 0,

    url: function() {
        return app.rest_root + 'submarkets/';
    },

    parse : function(response) {

        /* Set the location on the listingRouter */
        app.sublistingRouter.location = response.location;
        app.sublistingRouter.amenity_counts = response.amenity_counts;
        app.sublistingRouter.range = response.range;
        app.sublistingRouter.url_data.range = response.range;
        /* Pull the total count off of the response */
        this.total_count = response.count;
        this.subcity = response.subcity;
        this.page = response.page;

        return response.listings;
    }
});