<form action="" method="post" id="listing-form" class="std-form">
    <h3><% if (id) { %>Edit City<% } else { %>Add City<% } %></h3>

    <div class="row">
        <div class="col-lg-8">
            <div class="form-group">
                <label class="sr-only" for="city">City</label>
                <input type="text" class="form-control" id="city" placeholder="City"
                       name="city" value="<%= city %>"
                       data-rule-required="true">
            </div>
            <div class="form-group">
                <label class="sr-only" for="state">State</label>
                <?= form_states('state', '', false, "class='form-control'"); ?>
            </div>

            <% if (id) { %>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="sr-only" for="latitude">Latitude</label>
                            <input type="text" class="form-control" id="latitude" placeholder="Latitude"
                                   name="latitude" value="<%= latitude %>"
                                   disabled="disabled">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="sr-only" for="longitude">Longitude</label>
                            <input type="text" class="form-control" id="longitude" placeholder="Longitude"
                                   name="longitude" value="<%= longitude %>"
                                   disabled="disabled">
                        </div>
                    </div>
                </div>
            <% } %>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="is_popular">
                    <input type="checkbox" name="is_popular" value="1"
                           <% if (is_popular) { %>checked="checked"<% } %>/>
                    Show City?
                </label>
            </div>
        </div>
    </div>
<% if(id) { %>
    <div class="row">
        <hr/>
        <h4>Submarkets</h4>
        <div class="col-lg-6">
           <% _.each(submarket_list, function(sl) { %>
           <a href="/subedit/" id="sub-edit" sub-id="<%=sl.uuid%>"><%= sl.city %></a><br>
           <% }); %>
        </div>
        <divclass="col-lg-6">
           <button id="sub-add" href="sub/" data-id="<%=uuid%>" class="btn btn-primary pull-right btn-md">Add Submarket</button>
        </div>
    </div>
<% } %>

    <div class="row">
        <hr/>
        <h4>Photo</h4>

        <div class='photos'>
            <div class="row">
                <div class="photo-exists" <% if(!photo_original_url) { %>style="display:none"<%} %>>
                    <div class="photo col-lg-4 text-center">
                        <% if(photo_original_url){ %>
                        <img class="img-responsive" src="<%= photo_original_url %>"/>
                        <% } %>
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-sm btn-danger btn-delete">Delete</button>
                    </div>
                </div>

                <div class="photo-not-exists" <% if(photo_original_url) { %>style="display:none"<% } %>>
                    <div class="col-lg-12">
                        <input type="file" style="display:none" id="picture-upload-input" name="picture"/>
                        <a href="#" class="btn btn-sm btn-primary picture-upload-link" data-loading-text="Uploading..."
                           <% if (!id){ %>disabled="disabled"<% } %>>Add Photo</a>
                        <% if (!id) { %>
                            <span class="alert alert-info alert-picture-upload" style="padding:5px">You must save the new city first before you can add photos.</span>
                        <% } %>
                    </div>
               </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 submit-container">
            <a href="#delete_modal" data-toggle="modal" class="pull-left btn btn-sm"><i class="glyphicons bin"></i>
                Delete</a>
            <button class="submit btn btn-primary pull-right btn-md" data-loading-text="Saving..." tabindex="15">Save
            </button>
            <button class="cancel btn pull-right btn-md">Cancel</button>
            <div class="alert alert-success pull-right" style="display:none"></div>
            <div class="alert alert-danger pull-right" style="display:none"></div>
        </div>
    </div>
</form>


<div class="modal fade" id="delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Sure you wanna do that?</h2>
            </div>
            <div class="modal-body">
                <p>Deleting this city is permanent & cannot be undone.</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-delete-confirm" data-dismiss="modal">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->