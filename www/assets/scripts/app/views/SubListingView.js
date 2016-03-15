SubListingView = BaseView.extend({
    id: 'listing',
    className: 'listing',
    template_name: 'listing',

    events: {
        'click button.back': 'back_to_listings',
        'click button.btn-reserve': 'reserve',
        'click .actions .email a': 'email',
        'click .actions .print a': 'print',
        'click .btn-driving-directions': 'driving_directions',
        'click .btn-submit': 'submit'
    },

    back_to_listings: function () {
        window.history.back();
        return false;
    },

    driving_directions: function (event) {
        window.open('https://maps.google.com/maps?daddr=' + this.model.get('map_address'), "", "width=800,height=600");
        return false;
    },

    after_render: function () {
        var me = this;
        var center = {lat: parseFloat(this.model.get('latitude')), lng: parseFloat(this.model.get('longitude'))};
        this.map = new google.maps.Map(document.getElementById('gmap_canvas'), {
            center: center,
            zoom: 13
        });



        this.marker = new google.maps.Marker({
            position: center,
            map: this.map,
            title: this.model.get('address')
        });



        this.$el.find('form').validate();
        this.$el.find('form').ajaxForm({
            success: function(responseText, statusText, xhr, form) {
                //app.success_message('Your reservation has been received successfully.  We will get back to you shortly.', me.$el.find('.alert-container'));
                me.$el.find('.form-success').show();
                me.$el.find('form').hide();
            }
        });
    },

    reserve: function (event) {
        var me = this;
        /* Kill the old view */
        if (me.modalView) {
            me.modalView.remove();
        }
        /* Create a new view */
        me.reserveView = new ReserveView({
            model: me.model
        });
        me.modalView = new Backbone.BootstrapModal({
            content: me.reserveView,
            modalClass: 'reserve',
            allowOk: false,
            okText: 'SUBMIT',
            okCloses: false
        }).open();
        return false;
    },

    email: function (event) {
        var me = this;
        /* Kill the old view */
        if (me.modalView) {
            me.modalView.remove();
        }
        /* Create a new view */
        me.emailListingView = new EmailListingView({
            model: me.model
        });
        me.modalView = new Backbone.BootstrapModal({
            content: me.emailListingView,
            modalClass: 'email',
            allowOk: false,
            okText: 'SUBMIT',
            okCloses: false
        }).open();
        return false;
    },

    print: function (event) {
        window.print();
        return false;
    },

    submit: function (event) {
        this.$el.find('form').submit();
        return false;
    }
});

