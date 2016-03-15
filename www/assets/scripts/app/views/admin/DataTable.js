DataTableView = BaseView.extend({

    template_name: 'admin/datatable',

    table: undefined,

    events: {
        'click .dataTable tr td': 'edit'
    },

    initialize: function (options) {
        this.options = _.extend({
            remote: true,
            method: 'POST',
            perPage: 25,
            columns: [],
            sorting: [],
            bFilter: true,
            bLengthChange :true,
            bPaginate : true
        }, options);
    },

    render: function () {

        console.log(this.options);
        this.$el.html(this.template(this.options));
        if (this.options.add_text) {
        }

        this.render_table();
        /* Build the datatable */

        return this;
    },

    get_filter_params: function (aoData) {
        return;
    },

    render_table: function () {
        var me = this;

        this.table = this.$el.find('#datatable').dataTable({
            "sAjaxSource": this.options.url,
            "bServerSide": this.options.remote,
            "sServerMethod": this.options.method,
            "iDisplayLength": this.options.perPage,
            "aoColumns": this.options.columns,
            "aaSorting": this.options.sorting,
            "bFilter": this.options.bFilter,
            bLengthChange :this.options.bLengthChange,
            bPaginate : this.options.bPaginate,
            "fnServerParams": function(aodata) {
                me.get_filter_params(aodata);
            }
        });
    },

    edit: function (event) {
        var id = $(event.currentTarget).closest('tr').attr('id');
        if(id) {
            app.adminRouter.navigate(this.options.edit_url + id, true);
        }
        return false;
    }
});