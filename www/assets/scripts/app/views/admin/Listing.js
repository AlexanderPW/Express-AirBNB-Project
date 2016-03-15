ListingView = BaseFormView.extend({

    id: 'listing',

    template_name: 'admin/listing',

    events: {
        "click #delete_modal .btn-delete-confirm" : "delete_permanently",
        "click .submit-container button.cancel": "cancel",
        "change .unit_type" : "unit_type",
        "click .submit-container button.submit": "save",  /* BaseFormView.save() */
        "click .picture-upload-link" : "choose_picture",
        "change #picture-upload-input" : "submit_picture",
        "click .photo .btn-delete" : "delete_picture"
    },

    render: function () {
        BaseFormView.prototype.render.call(this);
        var me = this;

        var unit_types = this.model.get('unit_types');
        _.each(unit_types, function(unit_type) {
            me.$el.find('#unit_type_'+unit_type.unit_type_id).attr('checked', 'checked');
            me.$el.find('#unit_type_'+unit_type.unit_type_id+'_rate').removeAttr('disabled').val(unit_type.rate);
        });
        var amenities = this.model.get('amenities');
        _.each(amenities, function(amenity) {
            me.$el.find('#amenity_'+amenity.amenity_id).attr('checked', 'checked');
        });
        return this;
    },

    save_return : function() {
        this.$el.find('.picture-upload-link').removeAttr('disabled');
        this.$el.find('.alert-picture-upload').hide();
    },

    unit_type : function(event) {
        var id = $(event.currentTarget).attr('data-id');
        if($(event.currentTarget).is(':checked')) {
            this.$el.find('#unit_type_'+id+'_rate').removeAttr('disabled');
        } else {
            this.$el.find('#unit_type_'+id+'_rate').attr('disabled','disabled');
        }
    },

    cancel: function (event) {
        app.adminRouter.navigate('listings', true);
        return false;
    },

    delete_permanently : function(event) {
        var me = this;
        $.post( app.rest_root+'admin/listings/delete', {
            uuid: this.model.id
        }, function(response) {
            response = $.parseJSON(response);
            app.success_message(response.message, me.alert_container);
            app.adminRouter.navigate('listings', true);
        });
        return false;
    },

    choose_picture : function(event) {
        this.$el.find("#picture-upload-input").click();
        return false;
    },

    submit_picture : function(event) {
        var me = this;

        var files = $(event.currentTarget);
        var submit_button = this.$el.find('.picture-upload-link');
        submit_button.button('loading');

        $.ajax(app.rest_root+'admin/listings/picture', {
            iframe: true,
            files: files,
            data: {
                uuid: me.model.get('uuid')
            },
            dataType: 'json'
        }).done(function(response) {
                submit_button.button('reset');
                if(response.status=="error") {
                    var alert = me.$el.find('.submit-container .alert-danger');
                    alert.show().text(response.message);
                } else {
                    me.$el.find('.submit-container .alert-danger').html('');
                    var html = '<div class="photo col-lg-3 text-center"><img class="img-responsive" src="'+response.data.picture_url+'"/>' +
                        '<div class="clearfix"></div>' +
                        '<button class="btn btn-sm btn-danger btn-delete" data-id="'+response.data.photo_id+'">Delete</button></div>';
                    me.$el.find('.photos .row').append(html);
                }
            });
    },

    delete_picture : function(event) {
        var id = $(event.currentTarget).attr('data-id');
        $.ajax(app.rest_root+'admin/listings/picture_delete/'+id, {
            dataType: 'json'
        }).done(function(response) {
            $(event.currentTarget).closest('.photo').remove();
        });
        return false;
    }
});