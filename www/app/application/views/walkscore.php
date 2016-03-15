<html>
<head>

    <script type='text/javascript'>
        var ws_wsid = 'd21c7958221045e89e72d1890736cc6b';
        //var ws_address = '1018 Lombard Street, San Francisco, CA';
        var ws_lat=<?=$latitude?>;
        var ws_lon=<?=$longitude?>;
        var ws_width = '590';
        var ws_height = '460';
        var ws_layout = 'horizontal';
        var ws_hide_footer = 'true';
        var ws_commute = 'true';
        var ws_transit_score = 'true';
        var ws_map_modules = 'default';
        var ws_no_link_info_bubbles = 'true';
        var ws_no_link_score_description = 'true';
    </script>
    <style type='text/css'>#ws-walkscore-tile {
            position: relative;
            text-align: left
        }

        #ws-walkscore-tile * {
            float: none;
        }

        #ws-footer a, #ws-footer a:link {
            font: 11px/14px Verdana, Arial, Helvetica, sans-serif;
            margin-right: 6px;
            white-space: nowrap;
            padding: 0;
            color: #000;
            font-weight: bold;
            text-decoration: none
        }

        #ws-footer a:hover {
            color: #777;
            text-decoration: none
        }

        #ws-footer a:active {
            color: #b14900
        }</style>
</head>
<body>
<div id='ws-walkscore-tile'></div>
<script type='text/javascript' src='http://www.walkscore.com/tile/show-walkscore-tile.php'></script>
</body>
</html>