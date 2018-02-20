$(document).ready(function () {
    var $table = $('#table'),
        $button = $('#button'),
        $create = $('#create'),
        fileinput = $('#filebutton');
    var id = 0;

    checkToken();

    $(function() {
        fileinput.change(function (){
            var filename = fileinput.val();
            if (filename.split('.')[1] === 'xlsx'){
                var file = fileinput[0].files[0];
                fr = new FileReader();
                fr.readAsBinaryString(file);
                fr.onload = receivedFile
            } else {
                alert('file type not supported');
                fileinput.val('');
            }
        });
    });

    function receivedFile(e) {
        var bstr = e.target.result;
        var workbook = XLSX.read(bstr, {type:'binary'});
        console.log('workbook', workbook);
        var rows = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]]);
        rows.forEach(function (val) {
            requestNewAccount( val.surname.trim() + ' ' + val.name.trim(), val.domain)
        });
        fileinput.val('');
    }

    function checkToken() {
        var token = getToken();
        if (!token){
            login();
        }
        if (token.expires_in < moment().unix()){
            refreshToken(token.refresh_token)
        }

    }

    function getToken() {
        var data = localStorage.getItem('user');
        if (data){
            return JSON.parse(data);
        } else {
            login();
        }
    }

    function setToken(token) {
        localStorage.setItem('user', JSON.stringify(token));
    }

    function refreshToken(token){
        if (!token)
            login();
        var params = {
            grant_type: 'refresh_token',
            refresh_token: token
        };
        var request = $.post('/refresh', params);

        request.done(function (data) {
            data = JSON.parse(data);
            data.expires_in += moment().unix();
            setToken(data);
        });
    }

    function login() {
        window.location.href = host + 'login';
    }

    $table.bootstrapTable({
        showExport: true,
        clickToSelect: true,
        toolbar: '#toolbar',
        uniqueId: 'id',
        columns: [{
            title: '#',
            checkbox: true

        },{
            field: 'id',
            title: 'ID'
        },{
            field: 'name',
            title: 'Name'
        }, {
            field: 'login',
            title: 'Login'
        }, {
            field: 'password',
            title: 'Password'
        }, {
            field: 'email',
            title: 'Email'
        }, {
            field: 'status',
            title: 'Status'
        }]
    });



    $(function () {
        $button.click(function () {
            var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
                return row.id;
            });
            $table.bootstrapTable('remove', {
                field: 'id',
                values: ids
            });
        });
    });

    $(function () {
        $create.click(function () {
            var rows = $table.bootstrapTable('getSelections');
            for (var row in rows){
                createAccount(rows[row].login, rows[row].password, rows[row].email.split('@')[1], rows[row].id);
            }
        });
    });

    function createAccount(login, password, domain, id) {
        var data = {
            login: login,
            domain: domain,
            password: password,
            api_token: getToken().access_token
        };

        $.post('/account', data).done(function (data) {
            console.log(data);
            data = JSON.parse(data);
            var status = 'unknown';
            if (data.success === 'ok')
                status = data.success;
            else if (data.success === 'error')
                status = data.error;

            $table.bootstrapTable('updateByUniqueId', {
                id: id,
                row: {
                    status: status
                }
            });
        }).fail(function (data) {
            console.error(data);
        })
    }

    function requestNewAccount(name, domain){
        var $form = $('#form'),
            url = $form.attr('action');
        var data = {
            name: name,
            domain: domain,
            api_token: getToken().access_token
        };

        /* Send the data using post with element id name and name2*/
        $.post(url, data)
        /* Alerts the results */
            .done(function (data) {
                ++id;
                data.id = id;
                $('#table').bootstrapTable('append', data);
                $('#name').val('');
            })
            .fail(function (data) {
                alert('You do not have permissions or contact Administrator with below error: \n' + JSON.stringify(data));
            });
    }

    $('#form').submit(function (event) {
        event.preventDefault();
        requestNewAccount($('#name').val(), $('#domain').val());
    });
});