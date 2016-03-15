<div class="filter-bar well row">
    <form method="post">
        <div class="col-lg-2">
            Filter By:
        </div>
        <div class="col-lg-2">
            <label>Featured City:</label>
        </div>
        <div class="col-lg-4">
            <select id="featured_city_id" name="featured_city_id" class="form-control">
                <option selected="selected" value=""></option>
                <? $cities = city_list(); ?>
                <? foreach($cities as $city) { ?>
                    <option value="<?=$city->id?>"><?=$city->city?>, <?=$city->state?></option>
                <? } ?>
            </select>
        </div>
        <div class="clearfix"></div>
    </form>
</div>

<table id="datatable">
    <thead>
    <tr>
        <% _ . each(columns, function (column, index) { %>
            <th><%= column . title %></th>
        <% }) %>
    </tr>
    </thead>
</table>