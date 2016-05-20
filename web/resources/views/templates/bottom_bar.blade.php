<div class="dummy-fixed" ng-controller="WebSocketController">
    <div class="checkout">
        <a class="checkout__button" href="#">
                <span class="checkout__text">
                    <span class="checkout__text-inner checkout__initial-text">Micro / Text</span>
                </span>
        </a>
        <div class="checkout__order">
            <div class="checkout__order-inner">
                <div class="checkout__summary">
                    <ul class="chat chat-body row" style="overflow-y: scroll; outline: none; max-height: 300px;" id="chat">
                        <li ng-repeat="message in messages track by $index"
                            class="answer {$ message.author == username ? 'right' : 'left' $}">
                            <div class="avatar">
                                <img src="http://downloadicons.net/sites/default/files/user-group-icon-18526.png"
                                     alt="User name"
                                     class="img img-responsive">
                                <div class="status online"></div>
                            </div>
                            <div class="name" ng-bind="message.author"></div>
                            <div class="text" data-ng-bind-html="message.content"></div>
                            <div class="time" ng-bind="message.date"></div>
                        </li>
                    </ul>
                    <ul class="chat-body">
                        <li>
                            <form class="answer-add" ng-submit="sendMessage(); currentMessage = ''">
                                <input placeholder="Write a message" ng-model="currentMessage">
                                    <span class="answer-btn answer-btn-2"
                                          ng-click="sendMessage(); currentMessage = ''"></span>
                                    <span ng-click="toggleRecording()">
                                        <i class="fa {$ recording ? 'fa-stop' : 'fa-microphone' $} fa-2x fa-fw"></i>
                                    </span>
                            </form>
                        </li>
                    </ul>
                </div>
                <button class="checkout__close checkout__cancel"><i class="icon fa fa-fw fa-close"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>