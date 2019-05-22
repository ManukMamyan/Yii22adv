<!--<textarea id="chatMessages" cols="100" rows="10"></textarea>-->
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading" id="accordion">
                    <span class="glyphicon glyphicon-comment"></span> Chat
                    <div class="btn-group pull-right">
                        <a type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                    </div>
                </div>
                <div class="panel-collapse collapse" id="collapseOne">
                    <div class="panel-body">
                        <ul class="chat">

                        </ul>
                    </div>
                    <div class="panel-footer">
                        <div class="input-group">
                            <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." />
                            <input type="hidden" id="user" value="<?=Yii::$app->user->identity->username?>">
                            <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Send</button>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ?>