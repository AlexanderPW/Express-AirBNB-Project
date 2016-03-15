AdminView = BaseView.extend({

    el: '#page',
    template_name: 'admin/admin',

    events: {
        "click #sidebar li a": "route",
        "click #sub-add": "addsub",
        "click #sub-edit": "subedit",
        "click .action-container button": "btn_click"
    },

    render: function () {
        BaseView.prototype.render.call(this);

        /** Add the handling on the change email/password form -- have to do it here rather than in the events
         * section since its outside of the el **/
        $("#change_password_modal .btn-primary").click(this.change_email_password);

        return this;
    },

    route: function (event) {
        var href = $(event.currentTarget).attr('href');
        app.adminRouter.navigate(href, true);
        return false;
    },

     addsub: function (event) {
        var uid = $(event.currentTarget).attr('data-id');
        if (uid) {
        var href = $(event.currentTarget).attr('href');
        app.adminRouter.navigate(href + uid, true);
        return false;
    }
    },

    subedit: function (event) {
        var uid = $(event.currentTarget).attr('sub-id');
        if (uid) {
        var href = $(event.currentTarget).attr('href');
        app.adminRouter.navigate(href + uid, true);
        return false;
    }
    },

    set_page: function (title, active_class, btn_text, btn_callback) {
        this.$el.find('#sidebar li.active').removeClass('active');
        this.$el.find('#sidebar li.' + active_class).addClass('active');

        /* Empty out the header actions */
        this.$el.find('.action-container').empty();

        $('.page-title h1').text(title);
        document.title = app.site_title + " - "+title;
    },

    set_btn : function(btn_text, btn_callback) {
        if (btn_text) {
            this.$el.find('.action-container').append(
                '<button class="btn btn-md btn-primary pull-right">'+btn_text+'</button><div class="clearfix"></div>');
            this.btn_callback = btn_callback;
        }
    },

    btn_click: function (event) {
        if (this.btn_callback) {
            this.btn_callback(event);
        }
    },

    change_email_password: function(event) {
        if ($("#change_password_form").valid()) {
            $(event.currentTarget).button('loading');

            $.ajax(app.rest_root+'admin/users/change_password_email', {
                method: 'POST',
                data: {
                    username: $("#change-password-username").val(),
                    email: $("#change-password-email-address").val(),
                    password: $("#change-password-password").val()
                },
                dataType: 'json'
            }).done(function(response) {
                $(event.currentTarget).button('reset');
                app.success_message('Your email/password has been updated successfully.', $("#change_password_modal .alert-container"));
            });
        }
        return false;
    }
});