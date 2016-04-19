@section('javascript')
    @parent
    <script>
        var username = @if (Auth::check()) "{{ Auth::user()->name}}" @else 'user' + Math.floor((Math.random() * 100) + 1)@endif;
    </script>
@endsection

<div class="container-fluid" ng-init="open = false" ng-controller="HueChatController">
    <div class="col-xs-3 col-xs-offset-8 chatbox">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading" ng-click="open = !open">Quick Access</div>
                <div class="panel-body" ng-show="open">
                    <form class="answer-add" ng-submit="sendMessage(); currentMessage = ''">
                        <input placeholder="Write a message" ng-model="currentMessage">
                        <span class="answer-btn answer-btn-2" ng-click="sendMessage(); currentMessage = ''"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
