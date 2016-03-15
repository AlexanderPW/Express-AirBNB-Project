    <%
    city = _ . first(cities);
    cities = _ . rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_cityl = _.last(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    feature_photo1 = _.first(feature_cityl.photos);
    %>
<div class="row">
    <div class="col-lg-8 lc-first first">
        <div class="listing city" data-url="/listings/Featured/<%=city . url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
                <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
       <a href="/listing/<%=feature_city.url%>"> <div class="feature-2up col-sm-6" style="background-image:url('<%= feature_photo.original_url %>')">
        <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
       <a href="/listing/<%=feature_cityl.url%>"> <div class="feature-2up col-sm-6 hidden-xs" style="background-image:url('<%= feature_photo1.original_url %>')">
        <p class="title"><%= feature_cityl.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>

   <%
    city = _.first(cities);
    cities = _.rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    %>
    <div class="col-lg-4 lc-first">
        <div class="listing city" data-url="/listings/Featured/<%=city.url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
                <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
         <a href="/listing/<%=feature_city.url%>"><div class="feature-1up" style="background-image:url('<%= feature_photo.original_url %>')">
            <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>
    </div>
<div class="row">
   <%
    city = _.first(cities);
    cities = _.rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    %>
 <div class="col-lg-4 lc-first">
        <div class="listing city" data-url="/listings/Featured/<%=city.url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
                <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
        <a href="/listing/<%=feature_city.url%>"> <div class="feature-1up" style="background-image:url('<%= feature_photo.original_url %>')">
            <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>
     <%
    city = _.first(cities);
    cities = _.rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    %>
 <div class="col-lg-4 lc-first">
        <div class="listing city" data-url="/listings/Featured/<%=city.url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
               <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
        <a href="/listing/<%=feature_city.url%>"> <div class="feature-1up" style="background-image:url('<%= feature_photo.original_url %>')">
            <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>
     <%
    city = _.first(cities);
    cities = _.rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    %>
 <div class="col-lg-4 lc-first">
        <div class="listing city" data-url="/listings/Featured/<%=city.url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
               <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
       <a href="/listing/<%=feature_city.url%>">  <div class="feature-1up" style="background-image:url('<%= feature_photo.original_url %>')">
        <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>
</div>
<div class="row">

    <%
    city = _.first(cities);
    cities = _.rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    %>
    <div class="col-lg-4 lc-first">
        <div class="listing city" data-url="/listings/Featured/<%=city.url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
               <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
        <a href="/listing/<%=feature_city.url%>"> <div class="feature-1up" style="background-image:url('<%= feature_photo.original_url %>')">
            <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>
  <%
    city = _.first(cities);
    cities = _.rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_cityl = _.last(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    feature_photo1 = _.first(feature_cityl.photos);

    %>
    <div class="col-lg-8 lc-first">
        <div class="listing city" data-url="/listings/Featured/<%=city . url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
                <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
       <a href="/listing/<%=feature_city.url%>"> <div class="feature-2up col-sm-6" style="background-image:url('<%= feature_photo.original_url %>')">
        <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
      <a href="/listing/<%=feature_cityl.url%>">  <div class="feature-2up col-sm-6 hidden-xs" style="background-image:url('<%= feature_photo1.original_url %>')">
        <p class="title"><%= feature_cityl.address %></p><div class="featured-features"><% if (feature_cityl.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>
    </div>
        <%
    city = _.first(cities);
    cities = _.rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_cityl = _.last(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    feature_photo1 = _.first(feature_cityl.photos);
    %>
    <div class="row">
    <div class="col-lg-8 lc-first">
        <div class="listing city" data-url="/listings/Featured/<%=city . url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
                <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
       <a href="/listing/<%=feature_city.url%>"> <div class="feature-2up col-sm-6" style="background-image:url('<%= feature_photo.original_url %>')">
        <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
        <a href="/listing/<%=feature_cityl.url%>"><div class="feature-2up col-sm-6 hidden-xs" style="background-image:url('<%= feature_photo1.original_url %>')">
            <p class="title"><%= feature_cityl.address %></p><div class="featured-features"><% if (feature_cityl.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>
    <%
    city = _.first(cities);
    cities = _.rest(cities, 1);
    feature_city = _.first(city.listing_feature);
    feature_photo = _.first(feature_city.photos);
    %>
     <div class="col-lg-4 lc-first last">
        <div class="listing city" data-url="/listings/Featured/<%=city.url%>" style="background-image:url('<%= city.photo %>')">
            <h5>
                <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
        <a href="/listing/<%=feature_city.url%>"> <div class="feature-1up" style="background-image:url('<%= feature_photo.original_url %>')">
            <p class="title"><%= feature_city.address %></p><div class="featured-features"><% if (feature_city.is_pet_friendly == 1) { %><i class="fa fa-paw"></i> Pet Friendly<% }; %></div></div></a>
    </div>
    </div>
    <div class="row">
    <% _.each(cities, function(city, index) { %>
    <div class="col-lg-4">
        <div class="listing city" data-url="/listings/Featured/<%=city . url%>" style="background-image:url('<%= city.photo %>')">
            <h5 class="lc-last">
               <a href="featured-cities/<%=city.city%>">
                    <%=city.city%>
                </a>
            </h5>
        </div>
    </div>
    <% }); %>
</div>