@section('javascript')
    @parent
    <script>
        var username = @if (Auth::check()) "{{ Auth::user()->name}}" @else 'user' + Math.floor((Math.random() * 100) + 1)@endif;
    </script>
@endsection

<div class="container-fluid" ng-init="open = false" ng-controller="HueChatController">
    <div class="col-xs-5 col-xs-offset-5 chatbox">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading" ng-click="open = !open" style="font-size: 15px">Quick Access</div>
                <div class="panel-body" ng-show="open">
                    <div ng-repeat="msg in messages"  ng-bind-html="msg.content" style="font-size: 14px; color: black"></div>
                    <form class="answer-add" ng-submit="sendBotMessage(); currentMessage = ''">
                        <input placeholder="Write a message" ng-model="currentMessage" style="font-size: 17px; color: black">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
