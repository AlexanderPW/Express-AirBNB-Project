
<ul class="list-inline pull-right">
    <% for(var i=parseInt(pager_min); i<=parseInt(pager_max); i++) { %>
    <li <% if(i===current_page) { %>class="active"<% } %>><a href="#" data-page="<%=i%>"><%=i%></a></li>
    <% } %>
</ul>
<span class="pull-right">Page: </span>