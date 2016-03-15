<div class="row visible-print">

    <img src="/assets/images/logo.png" class="pull-left"/>

    <h3 class="pull-right">Call <?= $this->config->item('phone') ?> to Reserve Today!</h3>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <h2><%= address %>, <%= city %>, <%= state %> <%= zipcode %></h2>

            <p><%= description %></p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <h3>Amenities</h3>

            <ul class="amenities">
                <% _ . each(amenities, function (amenity) { %>
                    <li><%= amenity . name %></li>
                <% }) %>
            </ul>
        </div>
    </div>

    <div class="row">
        <% photo = _ . first(photos);
        if (photo) {
            %>
            <img class="photo pull-left" src="<%= photo . original_url %>"/>
        <% } %>
        <img
            src="http://maps.googleapis.com/maps/api/staticmap?center=<%= map_address %>&zoom=14&size=300x300&maptype=roadmap&markers=color:red%7C<%= latitude %>,<%= longitude %>&sensor=false"
            class="img-responsive map pull-right"/>
    </div>
</div>

<div class="row hidden-print">
    <div class="col-lg-5 col-sm-5">
        <% if (photos . length > 0) { %>
            <div class="img-holder">

                <% photo = _ . first(photos);
                photos = _ . rest(photos, 1);
                if (photo) {
                    %>
                    <a href="<%= photo . original_url %>" data-lightbox="<%= uuid %>"><img class="img-responsive"
                                                                                           src="<%= photo . original_url %>"/></a>

                    <ul class="thumbs list-inline hidden-xs">
                        <% _ . each(photos, function (photo) { %>
                            <li><a href="<%= photo . original_url %>" data-lightbox="<%= uuid %>"><img
                                        class="img-responsive" src="<%= photo . original_url %>"/></a></li>
                        <% }) %>
                    </ul>
                <% } %>
            </div>
        <% } %>

        <div class="btn-holder text-center">
            <button class="btn btn-lg btn-yellow-2 btn-driving-directions"><i class="glyphicons road"></i> Driving
                Directions
            </button>
        </div>

        <img
            src="http://maps.googleapis.com/maps/api/staticmap?center=<%= map_address %>&zoom=14&size=450x375&maptype=roadmap&markers=color:red%7C<%= latitude %>,<%= longitude %>&sensor=false"
            class="img-responsive map"/>

    </div>
    <div class="col-lg-7 col-sm-7 details">
        <button class="back">Back</button>

        <div class="title row">
            <div class="col-lg-12">
                <h1 class="pull-left"><%= address %></h1>

                <ul class="actions list-inline pull-right">
                    <li class="print"><a href=""><i class="glyphicons print"></i></a></li>
                    <!--<li class="favorite"><a href=""><i class="glyphicons heart"></i></a></li>-->
                    <li class="email"><a href=""><i class="glyphicons envelope"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="meta row">
            <div class="col-lg-12">
                <% if (is_pet_friendly) { %>
                    <span class="dog_friendly pull-left">Pet Friendly</span>
                <% } %>

                <span class="city pull-left"> <i class="glyphicons google_maps"></i> <%= city %>, <%= state %></span>
                <span class="phone pull-left"> <i
                        class="glyphicons earphone"></i> <?= $this->config->item('phone') ?></span>
                <span class="pull-right reserve">
                    <button class="btn btn-yellow-3 btn-simple btn-reserve"><i class="glyphicons edit"></i> Get Rates
                    </button>
                </span>
            </div>
        </div>

        <div class="levels">
            <table class="table">
                <tbody>
                <% _ . each(unit_types, function (type) { %>
                    <tr>
                        <td class="type"><%= type . name %></td>
                        <!--<td class="available"><%=type.available%> available</td>-->
                        <td class="reserve text-left">
                            <!--<button class="btn btn-yellow-3 btn-simple btn-reserve"><i class="glyphicons edit"></i> Get Rates</button>-->
                        </td>
                        <% if (type . rate > 0) { %>
                            <td class="pricing text-right">Starting At <%= accounting . formatMoney(type . rate) %></td>
                        <% } else { %>
                            <td class="pricing text-right">Call for Pricing</td>
                        <% } %>
                    </tr>
                <% }) %>
                </tbody>
            </table>
        </div>

        <div class="row description">
            <div class="col-lg-12">
                <h2>Description</h2>

                <p><%= description %></p>
            </div>
        </div>

        <div class="row amenities">
            <div class="col-lg-12">
                <h2>Amenities</h2>

                <ul class="list-inline">
                    <% _ . each(amenities, function (amenity) { %>
                        <li><%= amenity . name %></li>
                    <% }) %>
                </ul>
            </div>
        </div>

        <div class="community-amenities">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Community Amenities</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id='ws-walkscore-tile'></div>
                </div>
            </div>
        </div>
    </div>
</div>