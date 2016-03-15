var app = {
    root_url : '/',
    app_root : '/app/',
    rest_root : '/app/rest/',
    admin_root : '/app/admin/',

    success_message : function(message, container, delay) {
        if(!container.jquery || !container.empty) {
            container = $(container);
        }
        if(!delay) {
            delay = 5000;
        }

        if(container && message && container.empty) {
            container.empty().show();
            container.append('<p class="alert alert-success">'+message+'</p>');
            container.delay(delay).fadeOut(400);
        }
    },

    error_message : function(message, container, delay) {
        if(!container.jquery || !container.empty) {
            container = $(container);
        }
        if(!delay) {
            delay = 5000;
        }
        if(container && message && container.empty) {
            container.empty().show();
            container.append('<p class="alert alert-danger">'+message+'</p>');
            container.delay(delay).fadeOut(400);
        }
    },

    fetch_error : function(model_or_collection, response, options) {
        if(response && response.responseText) {
            if($('.alert-container').length>0) {
                app.error_message('<strong>Error:</strong> ' +response.responseText, $('.alert-container'), 99999);
            } else if(console) {
                console.error(response);
            }
        }
    }
};

jQuery(document).ready(function() {
    jQuery.ajaxSetup({
        cache: false
    });

})