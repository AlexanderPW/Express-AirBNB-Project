BaseFormView = BaseView.extend({

    render: function () {
        BaseView.prototype.render.call(this);

        var me = this;
        if(me.model) {
            this.$el.find('select').each(function () {
                var val = me.model.get($(this).attr('name'));
                if (typeof val != undefined && val != '') {
                    $(this).val(val);
                }
            });
        }

        /* Setup the form validation */
        this.$el.find('form').validate();

        return this;
    }

});