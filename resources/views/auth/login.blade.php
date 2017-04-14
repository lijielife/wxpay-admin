<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>源支付 - 后台管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/js/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css"> -->
    <script type="text/javascript" src="/assets/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap/js/bootstrap.min.js"></script>
    <style type="text/css">
    * {
        margin:0;
        padding: 0;
    }
    body {
        background: #444 url(/assets/images/carbon_fibre_big.png)}
    .loginBox {
        width:520px;
        height:250px;
        padding:0 20px;
        border:1px solid #fff;
        color:#000;
        margin-top:40px;
        border-radius:8px;
        background: white;
        box-shadow:0 0 15px #222;
        background: -moz-linear-gradient(top, #fff, #efefef 8%);
        background: -webkit-gradient(linear, 0 0, 0 100%, from(#f6f6f6), to(#f4f4f4));
        font:14px/1.5em 'Microsoft YaHei';
        position: absolute;
        left:50%;
        top:50%;
        margin-left:-210px;
        margin-top:-115px;
    }
    .loginBox h2 {
        height:45px;
        font-size:20px;
        font-weight:normal;
    }
    .loginBox .left {
        border-right:1px solid #ccc;
        height:100%;
        padding-right: 20px;
    }
    </style>
</head>
<body>
    <div class="container">
        <form role="form" action="/login" method="post">
            {!! csrf_field() !!}
            <div class="loginBox row">
                <div class="col-md-7 left">
                    <h2>登录</h2>
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="email" placeholder="邮箱/手机/编号"/>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" name="password" placeholder="密码"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label><input type="checkbox" name="remember" />下次自动登录</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <input type="submit" value=" 登录 " class="btn btn-primary">
                        </div>
                    </div>
                    @if(!empty($errors->all()))
                    <p class="bg-danger">
                        @foreach($errors->all() as $error)
                        {{ $error }}<br/>
                        @endforeach
                    </p>
                    @endif
                </div>
                <div class="col-md-5">
                    <h2>说明</h2>
                    <p>渠道、商户、收银员在此登陆进行管理。</p>
                    <p></p>
                </div>
            </div>
        </form>
    </div>
</body>
