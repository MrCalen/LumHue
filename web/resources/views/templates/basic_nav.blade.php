<!DOCTYPE html>
<html lang="en" @yield('ngApp') class="no-js">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LumHue @yield('title')</title>
    @include('templates/header')

    @section('title') @endsection

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/css/flat-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ URL::asset('css/nav.css') }}">
    @yield('css')
</head>
<body>
<div class="container" @yield('ngController')>
    <ul id="gn-menu" class="gn-menu-main">
        <li class="gn-trigger">
            <a class="gn-icon gn-icon-menu"><span>Menu</span></a>
            <nav class="gn-menu-wrapper">
                <div class="gn-scroller">
                    <ul class="gn-menu">
                        @yield('dropdown_options')
                        <li><a class="gn-icon gn-icon-download" href="{{ URL::to('/dashboard') }}">Dashboard</a></li>
                        <li><a class="gn-icon gn-icon-cog" href="{{ URL::to('/lights') }}">Lights</a></li>
                        <li><a class="gn-icon gn-icon-help" href="{{ URL::to('/ambiances') }}">Ambiances</a></li>
                        <li><a class="gn-icon gn-icon-archive">Log out</a></li>
                    </ul>
                </div>
            </nav>
        </li>
        <li><a href="#">LumHue</a></li>
        @yield('nav_options')
        <li></li>
    </ul>
    <header class="codrops-header">@yield('header')</header>
    <section>
        @yield('content')
    </section>
    <div class="dummy-fixed">
        <div class="checkout">
            <a class="checkout__button" href="#">
                <span class="checkout__text">
                    <span class="checkout__text-inner checkout__initial-text">Micro / Text</span>
                    <span class="checkout__text-inner checkout__final-text">Record</span>
                </span>
            </a>
            <div class="checkout__order">
                <div class="checkout__order-inner">
                    <button class="checkout__close checkout__cancel"><i class="icon fa fa-fw fa-close"></i>Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@yield('modals')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.5/angular-sanitize.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>

<script>
    (function () {
        var dummy = document.getElementById('dummy-grid');
        [].slice.call(document.querySelectorAll('.checkout')).forEach(function (el) {
            var openCtrl = el.querySelector('.checkout__button'),
                    closeCtrl = el.querySelector('.checkout__cancel');

            openCtrl.addEventListener('click', function (ev) {
                ev.preventDefault();
                classie.add(el, 'checkout--active');
                classie.add(dummy, 'dummy-grid--highlight');
            });

            closeCtrl.addEventListener('click', function () {
                classie.remove(el, 'checkout--active');
                classie.remove(dummy, 'dummy-grid--highlight');
            });
        });
    })();
</script>

<script>
    (function (window) {

        'use strict';

        function classReg(className) {
            return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
        }

        var hasClass, addClass, removeClass;

        if ('classList' in document.documentElement) {
            hasClass = function (elem, c) {
                return elem.classList.contains(c);
            };
            addClass = function (elem, c) {
                elem.classList.add(c);
            };
            removeClass = function (elem, c) {
                elem.classList.remove(c);
            };
        }
        else {
            hasClass = function (elem, c) {
                return classReg(c).test(elem.className);
            };
            addClass = function (elem, c) {
                if (!hasClass(elem, c)) {
                    elem.className = elem.className + ' ' + c;
                }
            };
            removeClass = function (elem, c) {
                elem.className = elem.className.replace(classReg(c), ' ');
            };
        }

        function toggleClass(elem, c) {
            var fn = hasClass(elem, c) ? removeClass : addClass;
            fn(elem, c);
        }

        var classie = {
            // full names
            hasClass: hasClass,
            addClass: addClass,
            removeClass: removeClass,
            toggleClass: toggleClass,
            // short names
            has: hasClass,
            add: addClass,
            remove: removeClass,
            toggle: toggleClass
        };

        if (typeof define === 'function' && define.amd) {
            define(classie);
        } else {
            window.classie = classie;
        }

    })(window);

</script>

<script>

    (function (window) {

        'use strict';

        function mobilecheck() {
            var check = false;
            (function (a) {
                if (/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4)))check = true
            })(navigator.userAgent || navigator.vendor || window.opera);
            return check;
        }

        function gnMenu(el, options) {
            this.el = el;
            this._init();
        }

        gnMenu.prototype = {
            _init: function () {
                this.trigger = this.el.querySelector('a.gn-icon-menu');
                this.menu = this.el.querySelector('nav.gn-menu-wrapper');
                this.isMenuOpen = false;
                this.eventtype = mobilecheck() ? 'touchstart' : 'click';
                this._initEvents();

                var self = this;
                this.bodyClickFn = function () {
                    self._closeMenu();
                    this.removeEventListener(self.eventtype, self.bodyClickFn);
                };
            },
            _initEvents: function () {
                var self = this;

                if (!mobilecheck()) {
                    this.trigger.addEventListener('mouseover', function (ev) {
                        self._openIconMenu();
                    });
                    this.trigger.addEventListener('mouseout', function (ev) {
                        self._closeIconMenu();
                    });

                    this.menu.addEventListener('mouseover', function (ev) {
                        self._openMenu();
                        document.addEventListener(self.eventtype, self.bodyClickFn);
                    });
                }
                this.trigger.addEventListener(this.eventtype, function (ev) {
                    ev.stopPropagation();
                    ev.preventDefault();
                    if (self.isMenuOpen) {
                        self._closeMenu();
                        document.removeEventListener(self.eventtype, self.bodyClickFn);
                    }
                    else {
                        self._openMenu();
                        document.addEventListener(self.eventtype, self.bodyClickFn);
                    }
                });
                this.menu.addEventListener(this.eventtype, function (ev) {
                    ev.stopPropagation();
                });
            },
            _openIconMenu: function () {
                classie.add(this.menu, 'gn-open-part');
            },
            _closeIconMenu: function () {
                classie.remove(this.menu, 'gn-open-part');
            },
            _openMenu: function () {
                if (this.isMenuOpen) return;
                classie.add(this.trigger, 'gn-selected');
                this.isMenuOpen = true;
                classie.add(this.menu, 'gn-open-all');
                this._closeIconMenu();
            },
            _closeMenu: function () {
                if (!this.isMenuOpen) return;
                classie.remove(this.trigger, 'gn-selected');
                this.isMenuOpen = false;
                classie.remove(this.menu, 'gn-open-all');
                this._closeIconMenu();
            }
        };

        window.gnMenu = gnMenu;

    })(window);
</script>

<script>
    new gnMenu(document.getElementById('gn-menu'));
</script>

<script>
    $(function () {
        window.isActive = true;
        $(window).focus(function () {
            this.isActive = true;
        });
        $(window).blur(function () {
            this.isActive = false;
        });
    });
</script>

@yield('javascript')

<script src="{{ URL::asset('components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.min.js')}}"></script>
<script src="{{ URL::asset('components/angular-ui-switch/angular-ui-switch.min.js')}}"></script>
<script src="{{ URL::asset('components/angular-drag-and-drop-lists/angular-drag-and-drop-lists.js')}}"></script>
<script src="{{ URL::asset('components/angular-local-storage/dist/angular-local-storage.min.js') }}"></script>
<script src="//www.WebRTC-Experiment.com/RecordRTC.js"></script>
</body>
</html>