<% photo = _ . first(photos); %>
<div class="hero" style="background-image:url('<%= photo . original_url %>')"></div>
<div class="title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="pull-left"><%= address %></h1>

                <ul class="pull-right list-inline">
                    <li>
                        <i class="fa fa-map-marker"></i> <%= city %>, <%= state %>
                    </li>
                    <li>
                        <i class="fa fa-phone"></i> <?= $this->config->item('phone') ?>
                    </li>
                    <% if (is_pet_friendly) { %>
                        <li>
                            <i class="fa fa-paw"></i>
                            Pet Friendly
                        </li>
                    <% } %>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="details">
    <div class="container">
        <div class="spacer-15"></div>
        <div class="row">
            <div class="col-lg-7">
                <h3>Description</h3>

                <p><%= description %></p>

                <div class="spacer-15"></div>
                <hr/>
                <div class="spacer-15"></div>
                 <h3>Transit</h3>
               
                <div class="col-md-5" id="transitscore">
                   
</div>
  <script type = "text/javascript" language = "javascript">
         jQuery(document).ready(function() {
             var addr = '<%= address %>';
             var city = '<%= city %>';
             var state = '<%= state %>';
             jQuery("#transitscore").load('/transitscore.php', {'addr':addr, 'city':city, 'state':state});
        });
      </script>

<div class="col-md-7">
     <p><i class="fa fa-bus"></i> Bus Stops:<br></p>
     <div  id="busstops">
<script type = "text/javascript" language = "javascript">
         jQuery(document).ready(function() {
             var lat = '<%= latitude %>';
             var lon = '<%= longitude %>';
             var city = '<%= city %>';
             var state = '<%= state %>';
             var listing_item = 'true';
             jQuery("#busstops").load('/listingstops.php', {'lat':lat, 'lon':lon, 'listing_item':listing_item});
        });
      </script></div>
</div>
                <p></p>

                <div class="spacer-15" style="clear:both"></div>
                <hr/>
                <div class="spacer-15"></div>

                <h3>Ammenities</h3>

                <ul class="amenities list-inline">
                    <% _ . each(amenities, function (amenity) { %>
                        <li><%= amenity . name %></li>
                    <% }) %>
                </ul>

                <div class="spacer-15"></div>
                <hr/>
                <div class="spacer-15"></div>
                 <div class="col-lg-12 map">
                <h3 class="caption-white">
                    Location
                </h3>

                <div id="gmap_canvas"></div>
               
            </div>
            </div>
            <div class="col-lg-5">
                <div class="reserve">
                    <h3>Reservation</h3>

                    <div class="alert alert-success form-success" style="margin-bottom: 0; display:none">
                        <p>Your reservation has been received successfully. We will get back to you shortly.</p>
                    </div>

                    <form method="post" action="/app/rest/reservations/" id="reservation-form">
                        <div class="form-group">
                            <label>Full Name
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="name" id="reservation-form-name" class="form-control"
                                   data-rule-required="true"/>
                        </div>

                        <div class="form-group">
                            <label>Email Address
                                <span class="required">*</span>
                            </label>
                            <input type="email" name="email" id="reservation-form-email" class="form-control"
                                   data-rule-required="true"/>
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" id="reservation-form-phone" class="form-control phone"
                                   placeholder="(555) 555-5555"/>
                        </div>

                        <div class="form-group type-select">
                            <label>Move-In Date
                                <span class="required">*</span>
                            </label>
                            <input type="text" class="date form-control" placeholder="MM/DD/YYYY" name="move_in_date"
                                   data-rule-required="true"/>
                        </div>

                        <div class="form-group type-select">
                            <label>Move-Out Date
                                <span class="required">*</span>
                            </label>
                            <input type="text" class="date form-control" placeholder="MM/DD/YYYY" name="move_out_date"
                                   data-rule-required="true"/>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group type-select">
                                    <label>Total Guests</label>
                                    <input type="text" class="small form-control" name="total_guests"/>
                                </div>

                                <div class="form-group type-select">
                                    <label># of Apartments
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="small form-control" name="number_of_apartments"
                                           data-rule-required="true"/>
                                </div>

                                <div class="form-group type-select">
                                    <label># of Bathrooms
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="small form-control" name="number_of_bathrooms"
                                           data-rule-required="true"/>
                                </div>
                            </div>

                            <div class="col-lg-6">

                                <div class="form-group type-select">
                                    <label>Monthly Budget</label>
                                    <input type="text" class="form-control money" name="budget" class="form-control"/>
                                </div>

                                <div class="form-group type-select">
                                    <label># of Bedrooms
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="small form-control" name="number_of_bedrooms"
                                           data-rule-required="true"/>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group type-select">
                                    <label for="c1" class="check">
                                        <input type="checkbox" id="c1" class="yes-no" value="1" name="pets"/>
                                        Bringing Pets
                                    </label>
                                </div>

                                <div class="form-group type-select">
                                    <label for="c3" class="toggle-label check">
                                        <input type="checkbox" id="c3" class="yes-no" value="1" name="government"/>
                                        Military or Government Employee
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group type-select">
                                    <label for="c2" class="toggle-label check">
                                        <input type="checkbox" id="c2" class="yes-no" value="1" name="housekeeping"/>
                                        Request Housekeeping
                                    </label>
                                </div>
                            </div>
                        </div>

                        <p class="text-center">
                            <button class="btn btn-primary btn-lg btn-submit">
                                Send Inquiry
                            </button>
                        </p>
                    </form>
                </div>
 <script type='text/javascript'>
        var ws_wsid = 'd21c7958221045e89e72d1890736cc6b';
        var ws_address = "<%= address %>, <%= city %>, <%= state %>";
        var ws_width = '100%';
        var ws_height = '460';
        var ws_layout = 'vertical';
        var ws_hide_footer = 'true';
        var ws_commute = 'true';
        var ws_transit_score = 'true';
        var ws_map_modules = 'default';
        var ws_no_link_info_bubbles = 'true';
        var ws_no_link_score_description = 'true';
    </script>
   
<div class="reserve">
     <h3>Community Amenities</h3>
     <style type='text/css'>#ws-walkscore-tile{padding:5px 15px !important;}</style>
<div id='ws-walkscore-tile'></div>
<script type='text/javascript' src='http://www.walkscore.com/tile/show-walkscore-tile.php'></script>
           </div> </div>
        </div>

        <div class="spacer-15"></div>

        <div class="row">
           
        </div>
    </div>
</div>
