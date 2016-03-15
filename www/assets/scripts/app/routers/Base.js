var BaseRouter = Backbone.Router.extend({
    container: '#backbone-container',
    alert_container: '.alert-container',
    template_cache: {},
    /* Non-routing stuff */

    start: function (root, defaultRoute) {
        if(!root) {
            root = '';
        }
        if (!Modernizr.history) {
            Backbone.history.start({ hashChange: true, root: root });
            if(defaultRoute)
                Backbone.history.loadUrl(defaultRoute);
        } else {
            Backbone.history.start({ pushState: true, root: root  });
        }
    },

    _renderViewComplex : function(view, callback) {
        app.template_cache.load([view.template_name], function () {
            view.render();
            if(callback) {
                callback();
            }
        });
    },

    _renderView: function (view, container, callback) {
        if(!container) {
            container = this.container;
        }
        app.template_cache.load([view.template_name], function () {
            $(container).empty().show();
            view.render().$el.appendTo(container);

            if(callback) {
                callback();
            }
        });
    },

    _renderCollectionView: function (view, collection, container, callback) {
        if(!container) {
            container = this.container;
        }
        app.template_cache.load([view.template_name], function () {
            $(container).empty();
            view.render(collection).$el.appendTo(container);

            if(callback) {
                callback();
            }
        });
    }
});

app.baseRouter = new BaseRouter;