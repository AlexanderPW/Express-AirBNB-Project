Listing = BaseModel.extend({
    urlRoot: app.rest_root + 'listings/listing',
    defaults : {
        'address' : '',
        'photos' : []
    }
});