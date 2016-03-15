CityView = BaseFormView.extend({

    id: 'city',

    template_name: 'admin/city',

    events: {
        "click #delete_modal .btn-delete-confirm" : "delete_permanently",
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save",  /* BaseFormView.save() */
        "click .picture-upload-link" : "choose_picture",
        "change #picture-upload-input" : "submit_picture",
        "click .photos .btn-delete" : "delete_picture"
    },

    render: function () {
        BaseFormView.prototype.render.call(this);
        var me = this;
        return this;
    },

    cancel: function (event) {
        app.adminRouter.navigate('cities', true);
        return false;
    },

    delete_permanently : function(event) {
        var me = this;
        $.post( app.rest_root+'admin/cities/delete', {
            uuid: this.model.id
        }, function(response) {
            response = $.parseJSON(response);
            app.success_message(response.message, me.alert_container);
            app.adminRouter.navigate('cities', true);
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

        $.ajax(app.rest_root+'admin/cities/picture', {
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
                    alert.show().text('The picture you are attempting to upload is too large.  Please choose a smaller image.');
                } else {
                    var html = '<img class="img-responsive" src="'+response.data.picture_url+'"/>';
                    me.$el.find('.photo-exists .photo').append(html);
                    me.$el.find('.photo-exists').show();
                    me.$el.find('.photo-not-exists').hide();
                }
            });
    },

    delete_picture : function(event) {
        var me = this;

        $.ajax(app.rest_root+'admin/cities/picture_delete/'+this.model.get('uuid'), {
            dataType: 'json'
        }).done(function(response) {
            me.$el.find('.photo-exists').hide();
            me.$el.find('.photo-exists .photo').empty();
            me.$el.find('.photo-not-exists').show();
        });
        return false;
    }
});