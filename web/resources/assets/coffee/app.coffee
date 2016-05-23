app = angular.module 'LumHue', [
  'colorpicker.module',
  'uiSwitch',
  'ngSanitize',
  'dndLists',
  'LocalStorageModule',
  'ngRoute'
  ], ($interpolateProvider) ->
  $interpolateProvider.startSymbol('{$');
  $interpolateProvider.endSymbol('$}');

app.config (localStorageServiceProvider) ->
    localStorageServiceProvider.setStorageCookie()

app.directive 'graphComponent', ($http) ->
  return {
    restrict: 'E'
    templateUrl: "graph-template.html"
    canvas_id: '@'
    lightid: '@'
    widgetid: '@'
    granularity: '@'
    ,
    link: (scope, elem, attrs) ->
      if attrs.lightid == '' or attrs.lightid == 'all'
        delete attrs.lightid
      else
        scope.lightid = attrs.lightid
      scope.granularity = attrs.granularity
      scope.sizes = [
        'small'
        'half'
        'medium'
        'large'
      ]
      scope.granularities = [
        'none'
        'days'
        'hours'
      ]

      datas = []
      scope.loading = true
      scope.widgetid = attrs.widgetid
      scope.fetchData = ->
        params =
          granularity: attrs.granularity
          access_token: window.token
        url = window.base_url + "/api/dashboard/lights"
        if attrs.lightid?
          params.light_id = attrs.lightid
          url = window.base_url + "/api/dashboard/light"

        $http.get(url,
          params: params
        )
        .success (data, status) ->
          datas = []
          if attrs.lightid?
            datas = [scope.getLight(data)]
          else
            for k, v of data
              datas.push scope.getLight v
          scope.loading = false
          scope.refresh()

      scope.fetchData()

      scope.getLight = (data) ->
        graph = []
        for date, value of data
          val = if value.on and value.reachable then 1 else  0
          graph.push [
            date
            val
          ]
        return graph

      scope.refresh = ->
        $("#canvas_dahs" + scope.widgetid).length && $.plot($("#canvas_dahs" + scope.widgetid), datas, {
          series: {
            lines: {
              show: false,
              fill: true
            },
            splines: {
              show: true,
              tension: 0.4,
              lineWidth: 1,
              fill: 0.4
            },
            points: {
              radius: 0,
              show: true
            },
            shadowSize: 2
          },
          grid: {
            verticalLines: true,
            hoverable: true,
            clickable: true,
            tickColor: "#d5d5d5",
            borderWidth: 1,
            color: '#fff'
          },
          colors: ["#87898A", "#87898A"],
          xaxis: {
            tickColor: "rgba(51, 51, 51, 0.06)",
            mode: "time",
            tickSize: [1, "day"],
            axisLabel: "Date",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 10
          },
          yaxis: {
            ticks: 8,
            tickColor: "rgba(51, 51, 51, 0.06)",
          },
          tooltip: false
        });
  }


app.directive 'activitiesComponent', ($http) ->
  return {
    restrict: 'E',
    templateUrl: 'activities-template.html',
    widgetid: '@'
    link: (scope, elem, attrs) ->
      scope.sizes = {
        'small'
        'half'
        'medium'
      }

      scope.widgetid = attrs.widgetid
      scope.loading = true
      url = window.base_url + "/api/dashboard/history"
      scope.refresh = ->
        params = {
          access_token: window.token
        }

        $http.get(url,
          params: params
        )
        .success (data, status) ->
          scope.messages = data
          scope.loading = false

      scope.refresh()
  }

app.directive 'weatherComponent', ($http, $timeout) ->
  return {
    restrict: 'E'
    templateUrl: 'weather-template.html'
    widgetid: '@'
    link: (scope, elem, attrs) ->
      scope.sizes = {
        'small'
        'half'
        'medium'
      }

      scope.widgetid = attrs.widgetid
      scope.loading = true
      url = window.base_url + "/api/dashboard/weather"
      scope.position = {}
      showPosition = (position) ->
        scope.position =
          lat: position.coords.latitude
          long: position.coords.longitude
        scope.refresh()

      navigator.geolocation.getCurrentPosition(showPosition);

      scope.toSkyCons = (main) ->
        switch main
          when "Clouds"
            return "cloudy"
          when "Clear"
            return "clear-day"
          else
            return main

      scope.refresh = ->
        params = {
          access_token: window.token
          lat: scope.position.lat
          long: scope.position.long
        }

        $http.get(url,
          params: params
        )
        .success (data, status) ->
          scope.result = data
          scope.days = data.list
          days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']

          for day in scope.days
            for weather in day.weather
              weather.main = scope.toSkyCons(weather.main)
            day.temperature = day.main.temp - 273.15
            day.temperature = Math.round(day.temperature * 10) / 10
            date = new Date(day.dt * 1000);
            day.dayname = days[date.getDay()]
          scope.today = data.list[0]
          icons = new Skycons({
            "color": "#000000"
          })
          list = [
              "clear-day", "clear-night", "partly-cloudy-day",
              "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
              "fog"
          ]
          $timeout ->
            for weatherType in list by -1
              elements = document.getElementsByClassName weatherType
              for e in elements
                icons.set e, weatherType
            icons.play();
            scope.loading = false
          , 200
  }

window.app = app