BaseView = Backbone.View.extend({

    template_name: '',
    alert_container: '.alert-container',
    child_views : [],

    template: function (data) {
        console.log(data);
        return _.template(app.template_cache.get(this.template_name), data);
    },

    initialize: function (options) {
        this.options = options;
    },

    render: function () {
        var attributes = this.options ? this.options : {};
        if (this.model) {
            attributes = this.model.toJSON();
        }
        this.$el.html(this.template(attributes));

        return this;
    },

    save: function (event, thisModel, callback, el) {

        if(!el || el.length<1) {
            el = this.$el;
        }

        if (el.find('form').valid()) {
            var attributes = el.find('form').serializeObject();
            var me = this;

            var submit_button = el.find('.submit-container button.submit');
            submit_button.button('loading');
            el.find('.submit-container .alert').hide();

            if(!thisModel) {
                thisModel = this.model;
            }

            thisModel.save(attributes, {
                success: function (model, response, options) {
                    if (response && response.message) {
                        var message = '<strong>Success: </strong> ' + response.message;
                        app.success_message(message, $('#main-alert-container'));
                        var alert = el.find('.submit-container .alert-success');
                        alert.html(message).show();

                        if (response.data && response.data.uuid) {
                            thisModel.set('uuid', response.data.uuid);
                        }
                    }
                    submit_button.button('reset');

                    if(callback) {
                        callback(response);
                    }

                    me.trigger('save_complete');
                    me.save_return();
                },
                error: function (model, xhr, options) {
                    var alert = el.find('.submit-container .alert-danger');

                    var errorMsg = '';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else {
                        errorMsg = '<strong>Error: </strong> There was an error with your submission.  Please try again';
                    }

                    alert.html(errorMsg).show();
                    submit_button.button('reset');
                }

            });
        }
        return false;
    },

    cancel: function (event) {
        if(this.return_url) {
            app.baseRouter.navigate(this.return_url, true);
        }
        return false;
    },

    save_return : function() {
        if(this.return_url) {
            app.baseRouter.navigate(this.return_url, true);
        }
        return false;
    },

    close: function(){
        this.remove();
        this.unbind();
        // handle other unbinding needs, here
        if(this.child_views.length>0) {
            _.each(this.child_views, function(child_view){
                if (child_view.close){
                    child_view.close();
                }
            })
        }
    }
});