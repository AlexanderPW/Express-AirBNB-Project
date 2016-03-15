<%
city = _ . first(cities);
cities = _ . rest(cities, 1);
%>
<div class="row">
    <div class="col-lg-12">
        <div class="listing city" data-url="/listings/Featured/<%=city . url%>" style="background-image:url('<%= city . photo %>')">
            <h5>
                <a href="/listings/Featured/<%=city . url%>">
                    <%=city . city%><%=city . city%>
                </a>
            </h5>
        </div>
    </div>
</div>
<div class="row">
    <%
    city = _ . first(cities);
    cities = _ . rest(cities, 1);
    %>
    <div class="col-lg-8">
        <div class="listing city" data-url="/listings/Featured/<%=city . url%>" style="background-image:url('<%= city . photo %>')">
            <h5>
                <a href="/listings/Featured/<%=city . url%>">
                    <%=city . city%>
                </a>
            </h5>
        </div>
    </div>
    <%
    city = _ . first(cities);
    cities = _ . rest(cities, 1);
    %>
    <div class="col-lg-4">
        <div class="listing city" data-url="/listings/Featured/<%=city . url%>" style="background-image:url('<%= city . photo %>')">
            <h5>
                <a href="/listings/Featured/<%=city . url%>">
                    <%=city . city%>
                </a>
            </h5>
        </div>
    </div>
</div>
<div class="row">
    <% _.each(cities, function(city, index) { %>
    <div class="col-lg-4">
        <div class="listing city" data-url="/listings/Featured/<%=city . url%>" style="background-image:url('<%= city . photo %>')">
            <h5>
                <a href="/listings/Featured/<%=city . url%>">
                    <%=city . city%>
                </a>
            </h5>
        </div>
    </div>
    <% }); %>
</div>