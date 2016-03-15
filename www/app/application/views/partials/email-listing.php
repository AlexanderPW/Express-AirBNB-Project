<h1 class="title"><i class="glyphicons envelope"></i> Email Your Friends</h1>

<div class="row header">
    <div class="col-lg-7">
        <% photo = _ . first(photos);
        if (photo) {
            %>
            <img class="img-responsive photo" src="<%= photo . original_url %>" width="128"/>
        <% } %>
        <label>Location</label>
        <h4><%= address %></h4>
        <h4><%= city %>, <%=state%> <%=zipcode%></h4>
    </div>

</div>


<div class="row">
    <div class="form">
        <div class="alert-container"></div>

        <form action="/app/rest/listings/email/<%=uuid%>" method="post">
            <div class="col-lg-12">
                <div class='form-group'>
                    <label>Enter Email Addresses <em>(Separated by commas)</em></label>
                    <input type="text" class="form-control" name="emails" data-rule-required="true"/>
                </div>

                <div class='form-group'>
                    <label>Your Personal Message</label>
                    <textarea rows="8" class="form-control" data-rule-required="true" name="message">Hey, checkout this cool apartment I found. Thought you might like it!</textarea>
                </div>

                <button type="submit" class="btn btn-primary pull-right">Send Email</button>
                <a href="#" class="pull-right cancel">Cancel</a>

                <div class="clearfix"></div>

            </div>
        </div>
    </div>
</div>