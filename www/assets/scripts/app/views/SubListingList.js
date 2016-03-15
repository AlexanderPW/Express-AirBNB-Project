SubListingListView = BaseListView.extend({
    id: 'listings-list',
    template_name: 'listings-list',
    listing: undefined,

    events: {
        'click button.details' : 'details',
        'click button.reserve' : 'reserve'
    },

    render: function (collection) {
        this.count = collection.count;
        BaseListView.prototype.render.call(this, collection);

        return this;
    },

    reserve: function (event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        var me = this;
        /* Kill the old view */
        if (me.modalView) {
            me.modalView.remove();
        }
        me.listing = new Listing({
            uuid: uuid
        });
        me.listing.fetch({
            success: function(model) {
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
            }
        });


        return false;
    },

    details : function(event) {
        var url = $(event.currentTarget).attr('data-url');
        app.sublistingRouter.navigate(app.sublistingRouter.root_url + 'submarket/'+url, true);
        return false;
    }
});