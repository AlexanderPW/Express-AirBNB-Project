DashboardView = BaseView.extend({

    id: 'dashboard',

    template_name: 'admin/dashboard',

    events: {
    },

    render: function () {
        BaseFormView.prototype.render.call(this);
        return this;
    }
});