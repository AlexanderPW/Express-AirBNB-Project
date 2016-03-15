<h1 class="title"><i class="glyphicons edit"></i> Reservation Form</h1>

<button type="button" class="close close-top" data-dismiss="modal" aria-hidden="true">&times;</button>
<form method="post" action="/app/rest/reservations/" id="reservation-form">
<% if(address) { %>
<div class="row header">
    <div class="col-lg-7">
        <% photo = _ . first(photos);
        if (photo) {
            %>
            <img class="img-responsive photo" src="<%= photo . original_url %>" width="128"/>
        <% } %>
        <label>Location</label>
        <h4><%= address %></h4>
        <h4><%= city %>, <%= state %> <%= zipcode %></h4>
    </div>
    <!--
    <div class="col-lg-5">
        <div class="form-group type-select">
            <label>Residence Type</label>
            <select name="type" class="form-control">
                <option value="2 Bed" selected="selected">2 Bedrooms - $1,500/mth</option>
            </select>
        </div>
    </div>-->
</div>
<% } %>
<div class="row">
    <ul class="steps list-inline">
        <li class="active step1">
            Reservation Info <span class="triangle"></span>
        </li>
        <li class="step2">
            Contact Info <span class="triangle"></span>
        </li>
        <li class="step3">
            Review & Submit <span class="triangle"></span>
        </li>
    </ul>
</div>

<div class="step1 step">
    <div class="row">
        <div class="form">
            <div class="col-lg-5">
                <div class="form-group type-select">
                    <label>Move-In Date<span class="required">*</span></label>
                    <input type="text" class="date form-control" placeholder="MM/DD/YYYY" name="move_in_date"
                           data-rule-required="true"/>
                </div>

                <div class="form-group type-select">
                    <label>Move-Out Date<span class="required">*</span></label>
                    <input type="text" class="date form-control" placeholder="MM/DD/YYYY" name="move_out_date"
                           data-rule-required="true"/>
                </div>

                <div class="form-group type-select">
                    <label>Total Guests</label>
                    <input type="text" class="small form-control" name="total_guests"/>
                </div>

                <div class="form-group type-select">
                    <label># of Apartments<span class="required">*</span></label>
                    <input type="text" class="small form-control" name="number_of_apartments"
                           data-rule-required="true"/>
                </div>

                <div class="form-group type-select">
                    <label># of Bedrooms<span class="required">*</span></label>
                    <input type="text" class="small form-control" name="number_of_bedrooms"
                           data-rule-required="true"/>
                </div>

                <div class="form-group type-select">
                    <label># of Bathrooms<span class="required">*</span></label>
                    <input type="text" class="small form-control" name="number_of_bathrooms"
                           data-rule-required="true"/>
                </div>
            </div>

            <div class="col-lg-5">
                <% if(!address) { %>
                <div class="form-group type-select">
                    <label>Location/City</label>
                    <input type="text" class="form-control" name="location" class="form-control"
                           data-rule-required="true"/>
                </div>
                <% } %>

                <div class="form-group type-select">
                    <label>Monthly Budget</label>
                    <input type="text" class="form-control money" name="budget" class="form-control"/>
                </div>

                <div class="form-group type-select">
                    <label>Bringing Pets?</label>

                    <div class="clearfix"></div>

                    <input type="checkbox" id="c1" class="yes-no" value="1" name="pets"/>
                    <label for="c1" class="toggle-label">Bringing Pets</label>
                </div>

                <div class="form-group type-select">
                    <label>Request Housekeeping?</label>

                    <div class="clearfix"></div>

                    <input type="checkbox" id="c2" class="yes-no" value="1" name="housekeeping"/>
                    <label for="c2" class="toggle-label">Request Housekeeping</label>
                </div>

                <div class="form-group type-select">
                    <label>Military or Government Employee?</label>

                    <div class="clearfix"></div>

                    <input type="checkbox" id="c3" class="yes-no" value="1" name="government"/>
                    <label for="c3" class="toggle-label">Military or Government Employee</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="buttons">
                <button class="btn btn-back pull-left" data-dismiss="modal">Cancel</button>

                <button class="btn btn-next pull-right">Next Step</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="step2 step" style="display:none">

    <div class="row">
        <div class="form">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Full Name<span class="required">*</span></label>
                    <input type="text" name="name" id="reservation-form-name" class="form-control"
                           data-rule-required="true"/>
                </div>

                <div class="form-group">
                    <label>Email Address<span class="required">*</span></label>
                    <input type="email" name="email" id="reservation-form-email" class="form-control"
                           data-rule-required="true"/>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" id="reservation-form-phone" class="form-control phone"
                           placeholder="(555) 555-5555"/>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group type-select">
                    <label>Notes<span class="required">*</span></label>
                    <textarea class="form-control" name="notes" class="form-control" rows="8"
                              id="reservation-form-notes" data-rule-required="true"></textarea>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="buttons">
                <button class="btn btn-back pull-left">Back</button>

                <button class="btn btn-next pull-right">Next Step</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="step3 step" style="display:none">
    <div class="row">
        <div class="col-lg-12">
            <h5 class="review-warning"><i class="glyphicons warning_sign"></i> Please review your information before
                submitting.</h5>
        </div>
    </div>
    <div class="row">
        <div class="form">
            <div class="col-lg-6 review-section">
                <div class="row">
                    <div class="col-lg-6 labels">
                        <% if(!address) { %>
                            <p><label>Location</label></p>
                        <% } %>
                        <p><label>Move-In Date</label></p>

                        <p><label>Move-Out Date</label></p>

                        <p><label>Total Guests</label></p>

                        <p><label># of Apartments</label></p>

                        <p><label># of Bedrooms</label></p>

                        <p><label># of Bathrooms</label></p>

                        <p><label>Monthly Budget</label></p>

                        <p><label>Bringing Pets?</label></p>

                        <p><label>Housekeeping?</label></p>

                        <p><label>Gov. Employee?</label></p>
                    </div>
                    <div class="col-lg-6">
                        <% if(!address) { %>
                            <p><span class="location">&nbsp;</span></p>
                        <% } %>
                        <p><span class="move_in_date">&nbsp;</span></p>

                        <p><span class="move_out_date">&nbsp;</span></p>

                        <p><span class="total_guests">&nbsp;</span></p>

                        <p><span class="number_of_apartments">&nbsp;</span></p>

                        <p><span class="number_of_bedrooms">&nbsp;</span></p>

                        <p><span class="number_of_bathrooms">&nbsp;</span></p>

                        <p><span class="budget">&nbsp;</span></p>

                        <p><span class="pets boolean">&nbsp;</span></p>

                        <p><span class="housekeeping boolean">&nbsp;</span></p>

                        <p><span class="government boolean">&nbsp;</span></p>

                    </div>
                </div>
                <button class="btn btn-white btn-edit" data-target="1">
                    <i class="glyphicons pencil"></i> Edit
                </button>
            </div>
            <div class="col-lg-6 review-section">
                <div class="row">
                    <div class="col-lg-6 labels">
                        <p><label>Full Name</label></p>

                        <p><label>Email Address</label></p>

                        <p><label>Phone Number</label></p>

                        <p><label>Notes</label></p>
                    </div>

                    <div class="col-lg-6">
                        <p><span class="name">&nbsp;</span></p>

                        <p><span class="email">&nbsp;</span></p>

                        <p><span class="phone">&nbsp;</span></p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12">
                        <p><span class="notes">&nbsp;</span></p>
                    </div>
                </div>
                <button class="btn btn-white btn-edit" data-target="2">
                    <i class="glyphicons pencil"></i> Edit
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="buttons">
                <button class="btn btn-back pull-left">Back</button>

                <button class="btn btn-primary btn-next pull-right">
                    <i class="glyphicons edit"></i> Send Inquiry
                </button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="step4 step" style="display:none">
    <div class="row">
        <div class="alert alert-success">Your inquiry has been sent successfully.  Someone from our office will be responding to you shortly.</div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="buttons">
                <button class="btn btn-next pull-right" data-dismiss="modal">Close</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<% if(address) { %>
    <input type="hidden" name="location_uuid" value="<%=uuid %>"/>
    <input type="hidden" name="location" value="<%=name%>, <%=address%>, <%= city %>, <%= state %> <%= zipcode %>"/>
<% } %>
</form>