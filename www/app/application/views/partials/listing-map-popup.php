<div class="listing">
    <div class="img-holder col-lg-4 col-md-4 col-xs-12  col-sm-6">
        <% photo = _.first(photos);
        if(photo) {
            %>
            <div class="main-image">
                <a href="<%=photo.original_url%>" data-lightbox="<%=uuid%>"><img class="img-responsive" src="<%=photo.original_url%>" /></a>
            </div>
        <% } %>
    </div>
    <div class="col-lg-8 col-xs-12 col-md-6 col-sm-6">
        <h3 class="pull-left"><%=  address %></h3>

        <div class="clearfix"></div>

        <h5><i class="glyphicons google_maps"></i> <%=  city %>, <%=state%></h5>
        <h5><i class="glyphicons earphone"></i> <?= $this->config->item('phone')?></h5>

        <div class="clearfix"></div>
        <p><%=  description_short %></p>


        <button class="details btn btn-primary" data-url="<%=url%>">Details</button>
        <button class="reserve btn btn-secondary">Reserve</button>
    </div>
</div>