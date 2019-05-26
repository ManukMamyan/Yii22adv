if (!window.WebSocket) {
    alert("Ваш браузер не поддерживает веб-сокеты!");
}

let webSocketPort = wsPort ? wsPort : 8080;
const conn = new WebSocket('ws://localhost:' + webSocketPort);

conn.onopen = function (e) {
    console.log("Connection established!");
};

document.getElementById("btn-chat")
    .addEventListener('click', function (event) {
        let textMesssage = document.getElementById('btn-input').value;
        let user = document.getElementById('user').value;
        let data = {textMesssage, user};

        const $li = $(`<li class="left clearfix"><span class="chat-img pull-left">
                            <img src="http://placehold.it/50/55C1E7/fff&text=Me" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">${data.user}</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span>${formatAMPM(new Date())}</small>
                                </div>
                                <p>
                                ${data.textMesssage}
                                </p>
                            </div>
                        </li>`);
        $('.chat').append($li);

        data = JSON.stringify(data);
        conn.send(data);
        return false;
    });

conn.onmessage = function (e) {
    let data = JSON.parse(e.data);
    const $li = $(`<li class="left clearfix"><span class="chat-img pull-left">
                            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">${data.user}</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span>${formatAMPM(new Date())}</small>
                                </div>
                                <p>
                                ${data.textMesssage}
                                </p>
                            </div>
                        </li>`);
    $('.chat').append($li);

    let $el = $('li.messages-menu ul.menu li:first').clone();
    $el.find('p').text(data.textMesssage);
    $el.find('h4').text(data.user);
    $el.prependTo('li.messages-menu ul.menu');

    let cnt = $('li.messages-menu ul.menu li').length;
    $('li.messages-menu span.label-success').text(cnt);
    $('li.messages-menu li.header').text('You have ' + cnt + ' messages');
};

function formatAMPM(date) {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    let strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

