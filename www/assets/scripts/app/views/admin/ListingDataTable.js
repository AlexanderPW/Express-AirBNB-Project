ListingDataTableView = DataTableView.extend({

    template_name: 'admin/datatable-listings',
    featured_city_id: 0,

    events: {
        'click .dataTable tr td': 'edit',
        'change #featured_city_id' : 'set_featured_city_id'
    },

    set_featured_city_id : function(event) {
        this.featured_city_id = $(event.currentTarget).val();
        this.table.fnDraw();
    },

    get_filter_params: function (aoData) {
        aoData.push({ "name":"featured_city_id", "value": this.$el.find('#featured_city_id').val() });
    }
});