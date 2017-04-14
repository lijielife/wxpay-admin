<p>
@if($type == 'channel')
亲爱的<font style="color:green">{{ $realname }}</font>，<br/>您在程序源支付的渠道"<font style="color:green">{{ $name }}</font>"已经激活通过，请关注程序源支付微信公众号Ypayer，并使用本邮箱+密码：<font style="color:red">{{ $password }}</font>，进行登陆绑定。
@elseif($type == 'merchant')
亲爱的<font style="color:green">{{ $realname }}</font>，<br/>您在程序源支付进件的商户“<font style="color:green">{{ $name }}</font>”已经激活通过，请关注程序源支付微信公众号Ypayer，并使用本邮箱+密码：<font style="color:red">{{ $password }}</font>，进行登陆绑定。
@elseif($type == 'cashier')
亲爱的<font style="color:green">{{ $realname }}</font>，<br/>您所在商户“<font style="color:green">{{ $name }}</font>”为您新增了收银员帐号，请关注程序源支付微信公众号Ypayer，并使用本邮箱+密码：<font style="color:red">{{ $password }}</font>，进行登陆绑定。
@endif
</p>
