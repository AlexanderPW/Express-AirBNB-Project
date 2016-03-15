<table id="datatable">
    <thead>
        <tr>
            <% _.each(columns, function(column, index) { %>
            <th><%=column.title%></th>
            <% }) %>
        </tr>
    </thead>
</table>