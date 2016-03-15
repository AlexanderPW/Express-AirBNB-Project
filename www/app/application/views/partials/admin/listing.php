<form action="" method="post" id="listing-form" class="std-form">
    <h3 class="pull-left"><% if(id) { %>Edit Listing<% } else { %>Add Listing<% } %></h3>
    <% if(id) { %>
    <a href="/listing/<%=uuid%>" target="_blank" class="pull-right btn btn-primary">View Listing</a>
    <% } %>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-8">
            <div class="form-group">
                <label class="sr-only" for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Name"
                       name="name" value="<%= name %>"
                       data-rule-required="true">
            </div>
            <div class="form-group">
                <label class="sr-only" for="name">HM GUID</label>
                <input type="text" class="form-control" id="hm_guid" placeholder="HM GUID"
                       name="hm_guid" value="<%= hm_guid %>"/>
            </div>
            <div class="form-group">
                <label class="sr-only" for="country">Country</label>
                <?= form_countries('country', 'US', false, "class='form-control' placeholder='country'"); ?>
            </div>
            <div class="form-group">
                <label class="sr-only" for="address">Address</label>
                <input type="text" class="form-control" id="address" placeholder="Address"
                       name="address" value="<%= address %>"
                       data-rule-required="true">
            </div>
            <div class="form-group">
                <label class="sr-only" for="city">City</label>
                <input type="text" class="form-control" id="city" placeholder="City"
                       name="city" value="<%= city %>"
                       data-rule-required="true">
            </div>
            <div class="form-group">
                <label class="sr-only" for="featured_city">Popular Destination</label>
                <?= form_city('featured_city_id', '', "class='form-control'"); ?>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <div class="form-group">
                        <label class="sr-only" for="state">State</label>
                        <?= form_states('state', '', false, "class='form-control'"); ?>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="sr-only" for="zipcode">Zipcode</label>
                        <input type="text" class="form-control" id="zipcode" placeholder="Zipcode"
                               name="zipcode" value="<%= zipcode %>"
                               data-rule-required="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="sr-only" for="bedrooms">Bedrooms</label>
                        <input type="text" class="form-control" id="bedrooms" placeholder="# of Bedrooms"
                               name="bedrooms" <% if(bedrooms>0) { %>value="<%= bedrooms %>"<% } %>>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="sr-only" for="longitude">Bathrooms</label>
                        <input type="text" class="form-control" id="bedrooms" placeholder="# of Bathrooms"
                               name="bathrooms" <% if(bathrooms>0) { %>value="<%= bathrooms %>"<% } %>>
                    </div>
                </div>
            </div>
            <% if(id) { %>
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
            <div class="form-group">
                <label class="sr-only" for="description">Short Description (Shown on Search Page)</label>
                <textarea class="form-control" placeholder="Short Description (Shown on Search Page)" name="description_short" id="description_short" rows="5"><%=description_short%></textarea>
            </div>
            <div class="form-group">
                <label class="sr-only" for="description">Description</label>
                <textarea class="form-control" placeholder="Description" name="description" id="description" rows="10"><%=description%></textarea>
            </div>

            <hr/>

            <h4>Unit Types</h4>

            <div class="row">
                <div class="col-lg-7">
                    <label>Unit Type</label>
                </div>
                <div class="col-lg-5">
                    <label>Daily Rate</label>
                </div>
            </div>
            <? foreach(object_list('unit_type') as $type) { ?>
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="unit_type[]" id="unit_type_<?=$type->id?>" value="<?=$type->id?>" class="unit_type" data-id="<?=$type->id?>"/>
                            <?=$type->name?>
                        </label>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" id="unit_type_<?=$type->id?>_rate"
                               name="unit_type_<?=$type->id?>_rate" disabled="disabled">
                    </div>
                </div>
            </div>
            <? } ?>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="is_published">
                    <input type="checkbox" name="is_published" value="1"
                           <% if(parseInt(is_published) > 0) {%>checked="checked"<% } %>/>
                    Published
                </label>
            </div>
            <div class="form-group">
                <label for="is_featured">
                    <input type="checkbox" name="is_featured" value="1"
                           <% if(parseInt(is_featured) > 0) {%>checked="checked"<% } %>/>
                    Featured
                </label>
            </div>
            <div class="form-group">
                <label for="is_pet_friendly">
                    <input type="checkbox" name="is_pet_friendly" value="1"
                           <% if(parseInt(is_pet_friendly) > 0) {%>checked="checked"<% } %>/>
                    Pet Friendly
                </label>
            </div>

            <h4>Amenities</h4>
            <div class="well">
                <? foreach(object_list('amenity') as $amenity) { ?>
                    <label for="amenity">
                        <input type="checkbox" name="amenity[]" value="<?=$amenity->id?>" id="amenity_<?=$amenity->id?>"/>
                        <?=$amenity->name?>
                    </label>
                    <div class="clearfix"></div>
                <? } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <hr/>
        <h4>Photos</h4>

        <div class='photos'>
            <div class="row">
                <% _.each(photos, function(photo, index) { %>
                    <div class="photo col-lg-3 text-center">
                        <img class="img-responsive" src="<%=photo.original_url%>"/>
                        <div class="clearfix"></div>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="<%=photo.id%>">Delete</button>
                    </div>
                    <% if((index+1)%4===0) { %>
                        <div class="clearfix"></div>
                    <% } %>
                <% }) %>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12">
                <input type="file" style="display:none" id="picture-upload-input" name="picture"/>
                <a href="#" class="btn btn-sm btn-primary picture-upload-link" data-loading-text="Uploading..." <% if(!id){ %>disabled="disabled"<% } %>>Add Photo</a>
                <% if(!id) { %>
                    <span class="alert alert-info alert-picture-upload" style="padding:5px">You must save the new listing first before you can add photos.</span>
                <% } %>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 submit-container">
            <a href="#delete_modal" data-toggle="modal" class="pull-left btn btn-sm"><i class="glyphicons bin"></i> Delete</a>
            <button class="submit btn btn-primary pull-right btn-md" data-loading-text="Saving..." tabindex="15">Save</button>
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
                <p>Deleting this listing is permanent & cannot be undone.</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-delete-confirm" data-dismiss="modal">Delete</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
