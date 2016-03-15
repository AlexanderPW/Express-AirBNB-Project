ComboListingListCollection = BaseListCollection.extend({
    model: City,
    url: function() {
        return app.rest_root + 'cities/combo';
    }
});