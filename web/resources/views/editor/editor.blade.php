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
    <link rel="stylesheet" href="{{ URL::asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <link rel="stylesheet" href=" {{ URL::asset('css/editor/editor.css') }}">
    <script src="{{ elixir('js/app.js') }}"></script>
    <script src="//misc.mr-calen.eu/js/blueprint3d.js"></script>
    <script src="/js/items.js"></script>
    <script src="/js/editor.js"></script>
</head>
<body>

<div class="container-fluid" ng-app="EditorModule" ng-controller="EditorController" id="body">
    <div class="container-fluid">
        <div class="row main-row">
            <div class="col-xs-4 sidebar">
                <div class="row light_info">
                    <div class="text-center" style="color: white">Your flat</div>
                    <hr/>
                </div>

                <div class="row">
                    <ul class="list-inline navbar-nav" style="margin-left: 20px">
                        <li>
                            <a href="{{ URL::to('/app') }}">
                                <i class="fa fa-home fa-white fa-fw" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="floorplan_tab">
                                <i class="fa fa-map fa-white fa-fw"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="design_tab">
                                <i class="fa fa-cubes fa-white fa-fw"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="items_tab">
                                <i class="fa fa-plus fa-white fa-fw"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <hr/>

                <!-- Context Menu -->
                <div id="context-menu" style="margin-bottom: 100px" ng-cloak>
                    <div style="margin: 0 20px">
                        <span id="context-menu-name" class="lead"></span>
                        <i class="fa fa-fw fa-trash fa-white pull-right" id="context-menu-delete"></i>
                        <br/>
                        <div ng-if="!currentItem || !currentItem.isBeacon">
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
                            <label><input type="checkbox" id="fixed"/> Lock in place</label>
                        </div>
                        <div ng-if="currentItem && currentItem.isLight">
                            <hr/>
                            <label id="lightids" class="option-label">Link this object to a lamp:</label>
                                <select aria-labelledby="lightids"
                                        ng-options="item for item in [1,2]"
                                        ng-model="currentItem.light_id"
                                        ng-change="addLightToItem()"
                                        class="form-control"></select>
                        </div>

                        <div ng-if="currentItem && currentItem.isBeacon">
                            <hr/>
                            <label class="option-label">On Enter: </label>
                            <select class="form-control"
                                    multiple
                                    ng-options="item.value as item.label for item in beaconOptions"
                                    ng-model="currentItem.enterLight"></select>
                            <hr width="50%"/>
                            <label class="option-label">On Leave: </label>
                            <select class="form-control"
                                    multiple
                                    ng-options="item.value as item.label for item in beaconOptions"
                                    ng-model="currentItem.leaveLight"></select>
                        </div>

                    </div>
                </div>

                <!-- Floor textures -->
                <div id="floorTexturesDiv" style="display:none; padding: 0 20px">
                    <div class="white">Floor Texture</div>
                    <div class="col-sm-6" style="padding: 3px">
                        <a href="#" class="thumbnail texture-select-thumbnail"
                           texture-url="/rooms/textures/light_fine_wood.jpg" texture-stretch="false"
                           texture-scale="300">
                            <img alt="Thumbnail light fine wood"
                                 src="/rooms/thumbnails/thumbnail_light_fine_wood.jpg"/>
                        </a>
                    </div>
                </div>

                <!-- Wall Textures -->
                <div id="wallTextures" style="display:none; padding: 0 20px">
                    <div class="">
                        <div class="white">Adjust Wall</div>
                        <div>
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

            <div class="col-xs-8 main">
                <div id="viewer">
                    <div id="main-controls">
                        <button href="#" class="btn btn-default btn-sm" ng-click="savePlan()">
                            Save Plan
                        </button>
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
                    <div class="row" id="items-wrapper"></div>
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