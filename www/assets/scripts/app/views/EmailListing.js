EmailListingView = BaseFormView.extend({
    id: 'email',
    template_name: 'email-listing',

    render: function () {
        BaseFormView.prototype.render.call(this);

        var me = this;
        this.$el.find('form').validate();

        this.$el.find('form').ajaxForm({
            success: function(responseText, statusText, xhr, form) {
                app.success_message('This listing has been successfully emailed to your friends.', me.$el.find('.alert-container'));
            }
        });
        return this;
    }
});