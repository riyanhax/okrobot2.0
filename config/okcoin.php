<?php
# ******************************************************
# author       : kaleo
# last modified: 2016-12-14 14:32
# email        : kaleo1990@hotmail.com
# filename     : okcoin.php
# description  : 
# ******************************************************
return[
'api_key' => "7573fd61-7b8a-4132-814b-9536325c8460",
'secret_key'=> "461D47D0FE52B28288E1285D8D899812",
'downline'=>3600,//初始化止损值
'upline'=>10000,//止盈值
'uprate'=>0.35,//上浮率
'downrate'=>0.25,//下浮动率
'unit'=>0.2,//下单单位
'unitrate'=>0.3,//买入，卖出对价值波动的比率
'klinetype'=>"30min",//kline的周期
'smsusername'=>"kaleozhou",//短信用户名
'smspassword'=>"zh13275747670",//短信密码
'smsphone'=>"13635456575"//短信手机号
];
