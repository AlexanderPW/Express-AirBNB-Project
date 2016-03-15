<% if(objects.length > 0) {
    var listing = objects[0];
    var total_count = listing.total_count;%>
<h3 class="title"><%= total_count %> Communities found</h3>
<% } else { %>
    <h3 class="title">No results found</h3>
    <p>There are no results matching the criteria you entered.  Please adjust your search.</p>
<% } %>
<% _.each(objects, function (listing) { %>
    <div class="listing row">
        <div class="img-holder col-lg-4 col-md-4 col-xs-12  col-sm-6">
            <% photo = _.first(listing.photos);
               if(photo) {
               photos = _.rest(listing.photos, 1).splice(0,3);
            %>
            <div class="main-image">
                <a href="<%=listing.url%>" data-lightbox="<%=listing.uuid%>"><img class="img-responsive" src="<%=photo.original_url%>" /></a>
                <% if(listing.is_featured>0) { %>
                <span class="is_featured tooltip-item" data-placement="top" data-toggle="tooltip" title="Featured Listing"></span>
                <% } %>
            </div>

            <ul class="thumbs list-inline hidden-xs">
                <% _.each(photos, function(photo) { %>
                <li><a href="<%=listing.url%>" data-lightbox="<%=listing.uuid%>"><img class="img-responsive" src="<%=photo.original_url%>" /></a></li>
                <% }) %>
            </ul>
            <% } %>
        </div>
        <div class="col-lg-6 col-xs-12 col-md-6 col-sm-6">
            <h3 class="pull-left"><a href="/listing/<%=listing.url%>"><%= listing . address %></a></h3>
            <span class="distance pull-right"><%= listing . distance %></span>

            <div class="clearfix"></div>

            <h5 class="pull-left"><i class="glyphicons google_maps"></i> <%= listing . city %>, <%= listing.state %></h5>
            <h5 class="pull-left"><i class="glyphicons earphone"></i> <?= $this->config->item('phone')?></h5>

            <div class="clearfix"></div>
            <p><%= listing . description_short %></p>

            <div class="bed-bath hidden-xs hidden-sm">
                <% if(listing.bedrooms > 0) { %>
                <span class="bed item">Up to <%= listing . bedrooms %></span>
                <% } %>
                <% if(listing.is_pet_friendly) { %>
                    <span class="dog_friendly pull-left">Pet Friendly</span>
                <% } %>
            </div>
        </div>
        <div class="col-lg-2 col-xs-12 col-sm-12 col-md-2 buttons">
            <button class="details" data-uuid="<%= listing . uuid %>" data-url="<%=listing.url%>">Details</button>
            <button class="reserve" data-uuid="<%= listing . uuid %>" data-url="<%=listing.url%>">Get Rates</button>
        </div>
    </div>
<% }); %>