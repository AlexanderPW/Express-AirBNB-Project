SubListing = BaseModel.extend({
    urlRoot: app.rest_root + 'submarkets/listing',
    defaults : {
        'address' : '',
        'photos' : []
    }
});