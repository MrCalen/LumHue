<html xmlns="http://www.w3.org/1999/html" class="no-js">

<head>
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.5/angular-sanitize.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
    <script src="/recorder.js"></script>

    <style type="text/css">
        html {
            overflow: hidden;
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
        }

        div {
            margin: 0;
            padding: 0;
        }

        /*
         * Sidebar
         */

        .main-row {
            padding: 0;
        }

        .sidebar {
            padding: 20px;
            overflow-x: hidden;
            overflow-y: auto;
            border-right: 1px solid #eee;
        }

        .nav-sidebar {
            margin-right: -21px; /* 20px padding + 1px border */
            margin-bottom: 20px;
            margin-left: -20px;
        }

        .nav-sidebar > li > a {
            padding-right: 20px;
            padding-left: 20px;
        }

        .nav-sidebar > .active > a,
        .nav-sidebar > .active > a:hover,
        .nav-sidebar > .active > a:focus {
            color: #fff;
            background-color: #428bca;
        }

        /*
         * Main content
         */

        .main {
            padding: 0;
        }

        /*
         * "Loading" modal
         */

        #loading-modal {
            position: absolute;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            padding: 20px;
            background-color: rgba(50, 50, 50, 0.9);
        }

        #loading-modal h1 {
            text-align: center;
            margin-top: 30%;
            color: #fff;
        }

        /*
         * Design
         */

        #viewer {
            display: none;
        }

        #floorplanner {
            display: none;
        }

        #add-items {
            display: none;
            padding: 20px;
            overflow-y: auto;
        }

        #main-controls {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 0;
        }

        #camera-controls {
            position: absolute;
            bottom: 20px;
            right: 0;
            padding: 0 20px;
            text-align: right;
        }

        #floorplanner-controls {
            position: absolute;
            left: 0;
            top: 0;
            margin: 20px 0;
            padding: 0 20px;
            width: 100%;
        }

        #draw-walls-hint {
            position: absolute;
            left: 20px;
            bottom: 20px;
            background-color: rgba(0, 0, 0, 0.50);
            color: #ffffff;
            padding: 5px 10px;
            z-index: 10;
            display: none;
        }

        .add-item {
            cursor: pointer;
        }

        .btn-file {
            display: inline-block;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            filter: alpha(opacity=0);
            opacity: 0;
            cursor: inherit;
            display: block;
        }
    </style>
    <script src="{{ elixir('js/app.js') }}"></script>
    <script src="//misc.mr-calen.eu/js/blueprint3d.js"></script>
    <script src="/js/items.js"></script>
    <script src="/js/editor.js"></script>
</head>
<body>

<div class="container-fluid" ng-app="EditorModule" ng-controller="EditorController">
    <div class="container-fluid">
        <div class="row main-row">
            <div class="col-xs-3 sidebar">
                <ul class="nav nav-sidebar">
                    <li id="floorplan_tab"><a href="#">
                            Edit Floorplan
                            <span class="glyphicon glyphicon-chevron-right pull-right"></span>
                        </a></li>
                    <li id="design_tab"><a href="#">
                            Design
                            <span class="glyphicon glyphicon-chevron-right pull-right"></span>
                        </a></li>
                    <li id="items_tab"><a href="#">
                            Add Items
                            <span class="glyphicon glyphicon-chevron-right pull-right"></span>
                        </a></li>
                </ul>
                <hr/>

                <!-- Context Menu -->
                <div id="context-menu">
                    <div style="margin: 0 20px">
                        <span id="context-menu-name" class="lead"></span>
                        <br/><br/>
                        <button class="btn btn-block btn-danger" id="context-menu-delete">
                            <span class="glyphicon glyphicon-trash"></span>
                            Delete Item
                        </button>
                        <br/>
                        <div class="panel panel-default">
                            <div class="panel-heading">Adjust Size</div>
                            <div class="panel-body" style="color: #333333">

                                <div class="form form-horizontal" class="lead">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">
                                            Width
                                        </label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" id="item-width">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">
                                            Depth
                                        </label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" id="item-depth">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">
                                            Height
                                        </label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" id="item-height">
                                        </div>
                                    </div>
                                </div>
                                <small><span class="text-muted">Measurements in inches.</span></small>
                            </div>
                        </div>

                        <label><input type="checkbox" id="fixed"/> Lock in place</label>
                        <br/><br/>
                    </div>
                </div>

                <!-- Floor textures -->
                <div id="floorTexturesDiv" style="display:none; padding: 0 20px">
                    <div class="panel panel-default">
                        <div class="panel-heading">Adjust Floor</div>
                        <div class="panel-body" style="color: #333333">

                            <div class="col-sm-6" style="padding: 3px">
                                <a href="#" class="thumbnail texture-select-thumbnail"
                                   texture-url="/rooms/textures/light_fine_wood.jpg" texture-stretch="false"
                                   texture-scale="300">
                                    <img alt="Thumbnail light fine wood"
                                         src="/rooms/thumbnails/thumbnail_light_fine_wood.jpg"/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wall Textures -->
                <div id="wallTextures" style="display:none; padding: 0 20px">
                    <div class="panel panel-default">
                        <div class="panel-heading">Adjust Wall</div>
                        <div class="panel-body" style="color: #333333">
                            <div class="col-sm-6" style="padding: 3px">
                                <a href="#" class="thumbnail texture-select-thumbnail"
                                   texture-url="/rooms/textures/marbletiles.jpg" texture-stretch="false"
                                   texture-scale="300">
                                    <img alt="Thumbnail marbletiles" src="/rooms/thumbnails/thumbnail_marbletiles.jpg"/>
                                </a>
                            </div>
                            <div class="col-sm-6" style="padding: 3px">
                                <a href="#" class="thumbnail texture-select-thumbnail"
                                   texture-url="/rooms/textures/wallmap_yellow.png" texture-stretch="true"
                                   texture-scale="">
                                    <img alt="Thumbnail wallmap yellow"
                                         src="/rooms/thumbnails/thumbnail_wallmap_yellow.png"/>
                                </a>
                            </div>
                            <div class="col-sm-6" style="padding: 3px">
                                <a href="#" class="thumbnail texture-select-thumbnail"
                                   texture-url="/rooms/textures/light_brick.jpg" texture-stretch="false"
                                   texture-scale="100">
                                    <img alt="Thumbnail light brick" src="/rooms/thumbnails/thumbnail_light_brick.jpg"/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-9 main">
                <input type="file" class="" id="loadFile">
                <div id="viewer">

                    <div id="main-controls">
                        <a href="#" class="btn btn-default btn-sm" id="new">
                            New Plan
                        </a>
                        <a href="#" class="btn btn-default btn-sm" ng-click="savePlan()">
                            Save Plan
                        </a>
                        <a class="btn btn-sm btn-default btn-file">
                            Load Plan
                        </a>
                    </div>

                    <div id="loading-modal">
                        <h1>Loading...</h1>
                    </div>
                </div>

                <div id="floorplanner">
                    <canvas id="floorplanner-canvas"></canvas>
                    <div id="floorplanner-controls">

                        <button id="move" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-move"></span>
                            Move Walls
                        </button>
                        <button id="draw" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-pencil"></span>
                            Draw Walls
                        </button>
                        <button id="delete" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-remove"></span>
                            Delete Walls
                        </button>
                    </div>
                </div>
                <div id="add-items">
                    <div class="row" id="items-wrapper">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    var token = '{{ $token }}';
    var username = "{{ Auth::user()->name}}";
    var base_url = '{{ URL::to('/') }}';
            @if (isset($data))
    var data = '{!! json_encode($data) !!}';
    @endif

</script>
<script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>