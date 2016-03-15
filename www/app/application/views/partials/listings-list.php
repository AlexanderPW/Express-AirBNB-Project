<div class="container">
    <div class="title-container">
        <% if (objects . length > 0) {
            var listing = objects[0];
            var total_count = listing . total_count; %>
            <h3 class="title"><%= total_count %> Communities found</h3>
      <div id="subcity-holder"></div>
        <% } else { %>
            <h3 class="title">No results found</h3>
            <p>There are no results matching the criteria you entered. Please adjust your search.</p>
        <% } %>
    </div>
    <% _ . each(objects, function (listing) { %>
        <div class="row">
            <div class="listing">
                <div class="img-holder col-lg-5 col-xs-12 col-sm-4">
                    <ul class="thumbs list-unstyled">
                        <% _ . each(listing . photos, function (photo) { %>
                            <li>
                                <a href="/listing/<%=listing . url%>">
                                    <img class="img-responsive" src="<%=photo . original_url%>"/>
                                </a>
                            </li>
                        <% }) %>
                    </ul>
                </div>
                <div class="details-holder col-lg-7 col-xs-12 col-sm-8">
                    <h3 class="pull-left col-md-10 croptext">
                        <a href="/listing/<%=listing . url%>"><%= listing . address %></a>
                    </h3>
                    <span class="distance pull-right distance-listing"><%= listing . distance %></span>

                    <div class="clearfix"></div>

                    <hr/>
                    <span class="distance distance-map"><%= listing . distance %></span>

                    <ul class="features list-unstyled">
                        <li>
                            <i class="fa fa-map-marker"></i> <%= listing . city %>, <%= listing . state %>
                        </li>
                        <li>
                            <i class="fa fa-phone"></i> <?= $this->config->item('phone')?>
                        </li>

                        <% if (listing . is_pet_friendly) { %>
                            <li>
                                <i class="fa fa-paw"></i>
                                Pet Friendly
                            </li>
                        <% } %>

                        <% if (listing . bedrooms > 0) { %>
                            <li>
                                <i class="fa fa-home"></i>
                                Up to <%= listing . bedrooms %> bedrooms
                            </li>
                        <% } %>
                    </ul>

                    <div class="clearfix"></div>
                    <p class="croptext"><%= listing . description_short %></p>


                   <div class="listings-transit"><i class="fa fa-bus"></i> Transit Routes:<br>
                 
       <!-- http://transit.walkscore.com/transit/search/stops/?lat=47.6101359&lon=-122.3420567& wsapikey=0c4f1575f9b47634688d1950ed736fc4-->
      <div id="<%= listing . uuid %>" class="stops col-md-7">
<script type = "text/javascript" language = "javascript">
         jQuery(document).ready(function() {
             var lat = '<%= listing . latitude %>';
             var lon = '<%= listing . longitude %>';
             var city = '<%= listing.city %>';
             var state = '<%= listing.state %>';
             jQuery("#<%= listing . uuid %>").load('/listingstops.php', {'lat':lat, 'lon':lon, 'city':city, 'state':state});
        });
      </script></div></div>


<div class="col-md-5 map-list" id="<%= listing.uuid %>2"><script type = "text/javascript" language = "javascript">
         jQuery(document).ready(function() {
             var addr = '<%= listing.address %>';
             var city = '<%= listing.city %>';
             var state = '<%= listing.state %>';
             jQuery("#<%= listing.uuid %>2").load('/transitscore.php', {'addr':addr, 'city':city, 'state':state});
        });
      </script></div>
                    <div class="btns">
                        <button class="details btn-details btn" data-uuid="<%= listing . uuid %>" data-url="<%=listing . url%>">
                            <i class="fa fa-list"></i>
                            Details
                        </button>
                        <button class="reserve btn-reserve btn" data-uuid="<%= listing . uuid %>" data-url="<%=listing . url%>">
                            <i class="fa fa-edit"></i>
                            Get Rates
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <% }); %>
</div>


