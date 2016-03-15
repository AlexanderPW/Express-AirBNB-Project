ListingPagerView = BaseView.extend({
    template_name: 'listings-pager',
    ADJACENTS: 3,

    events: {
        'click li a' : 'page'
    },

    render: function () {
        var total_pages = (this.options.collection.total_count / this.options.parent.LISTING_LIMIT);
        var last_page = Math.ceil(this.options.collection.total_count/this.options.parent.LISTING_LIMIT);
        var page = this.options.collection.page;

        var pager_min = 1;
        var pager_max = Math.min(this.ADJACENTS + 1, total_pages);

        if(last_page > 1) {
            if(last_page >= this.ADJACENTS + (this.ADJACENTS * 2)) {
                /* We're in the beginning of the list of pages */
                if(page < (this.ADJACENTS * 2)-1) {
                    pager_min = 1;
                    pager_max = (this.ADJACENTS * 2)
                }
                /* We're in the middle, hide some pages */
                else if(last_page - (this.ADJACENTS * 2)+1 > page && page >= (this.ADJACENTS * 2)-1) {
                    pager_min = page - this.ADJACENTS;
                    pager_max = page + this.ADJACENTS;
                } else {
                    pager_min = last_page - (this.ADJACENTS * 2);
                    pager_max = last_page;
                }
            } else {
                pager_min = 1;
                pager_max = last_page;

            }

            var attributes = {
                pager_min : pager_min,
                pager_max : pager_max,
                current_page : page
            };

            this.$el.html(this.template(attributes));
        }


        return this;
    },

    page : function(event) {
        var page = $(event.currentTarget).attr('data-page');
        this.options.parent.LISTING_START = this.options.parent.LISTING_LIMIT * (page - 1);
        this.trigger('pageUpdate');
        $('html,body').animate({
            scrollTop: $('.listings-list').offset().top
        }, 300);
        return false;
    }

});