<div class="row">
    <% _.each(listings, function(listing, index) { %>
        <% photo = _.first(listing.photos); %>
        <div class="col-lg-4 col-sm-4">
            <div class="listing item" data-id="<%=listing . uuid%>" data-url="/listing/<%=listing . url%>" style="background-image:url('<%= photo . original_url %>')">
                <h5>
                    <a href="/listing/<%=listing . url%>"><%= listing . address %></a>
                </h5>
            </div>
        </div>
    <% }); %>
</div>