<div class="searchbar">
    <div class="search">
        <div class="container">
            <form action="/app/search/" method="post">

                <div class="toggle">
                    <a href="#" class="list-view active">
                        <i class="fa fa-list"></i>
                    </a>
                    <a href="#" class="map-view">
                        <i class="fa fa-map-marker"></i>
                    </a>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="state" class="sr-only">State (US & Canada)</label>
                            <?= form_states('state', '', true, 'class="form-control"'); ?>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="city_zip" class="sr-only">City or Zip Code</label>
                            <input type="text" class="form-control" id="cityzip" name="cityzip">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="state" class="sr-only">Radius Search</label>
                            <select class="form-control" name="range" id="range">
                                <option value="10">10 Miles</option>
                                <option value="25">25 Miles</option>
                                <option value="50">50 Miles</option>
                                <option value="75">75 Miles</option>
                                <option value="100">100 Miles</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn-primary btn btn-block">Update</button>
                    </div>
                </div>

                <hr/>

                <h4 class="dotted">Filter Result
                    <i class="fa fa-filter"></i>
                </h4>

                <div class="row">

                </div>
                <!--
                <div class="form-group listings-type">
                    <label class="btn active">
                        <input type="radio" name="listings_type" id="listings-all" value="all"> All
                    </label>
                    <label class="btn active">
                        <input type="radio" name="listings_type" id="listings-featured" value="featured"> Featured
                    </label>
                </div>

                <div class="form-group bedrooms">
                    <label>Bedrooms</label>

                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn active">
                            <input type="radio" name="bedrooms" id="bedrooms-all" value="0"> All
                        </label>
                        <label class="btn">
                            <input type="radio" name="bedrooms" id="bedrooms-1" value="1"> 1
                        </label>
                        <label class="btn">
                            <input type="radio" name="bedrooms" id="bedrooms-2" value="2"> 2
                        </label>
                        <label class="btn">
                            <input type="radio" name="bedrooms" id="bedrooms-3" value="3"> 3
                        </label>
                        <label class="btn">
                            <input type="radio" name="bedrooms" id="bedrooms-4" value="4"> 4+
                        </label>
                    </div>
                </div>

                <label for="amenities">Ammenities</label>

                <div class="amenities">
                    <?
                    foreach(object_list('amenity') as $amenity) { ?>
                    <label id="amenity-<?=$amenity->id?>">
                        <input type="checkbox" name="amenity" value="<?=$amenity->id?>"/><?=$amenity->name?> <em></em>
                    </label>

                    <? if($amenity->id==10) { ?>
                    <div class="more" style="display:none">
                        <? } ?>
                        <div class="clearfix"></div>
                        <? } ?>
                    </div>
                    <a class="see-more" href="#">See More Amenities   &#149;  &#149;  &#149;</a>
                </div>
                -->
                <input type="hidden" name="mode" id="mode"/>
            </form>
        </div>
    </div>
</div>