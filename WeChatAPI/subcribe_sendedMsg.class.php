<?php
class Index {
    public function index(){
        //获得参数 signature nonce token timestamp
        $timestamp = $_GET['timestamp'];
		$nonce = $_GET['nonce'];
		$token = 'JackeyEtingLove';
		$signature = $_GET['signature'];
		$echostr = $_GET['echostr'];
		$array = array($timestamp,$nonce,$token);
		sort($array);
        //合并成字符串并sha1加密
       $str = sha1(implode('',$array));
       if ($str == $signature && $echostr) {//判断该数据源是不是微信后台的
       	//第一次接入weixin api 接口的时候
       	echo $echostr;
       	exit;
       }else{
       	$this->responsMsg();
       }
    }
    //接收事件推送并回复
	public function responsMsg(){
		//1、获取到微信推送过来的post数据(xml格式)
		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
		$tmpstr = $postArr;//获取数据格式
		//2、处理消息类型 ：取消关注 ，关注 ；并设置回复类型和内容
/*<xml>
<ToUserName><![CDATA[toUser]]></ToUserName>
<FromUserName><![CDATA[FromUser]]></FromUserName>
<CreateTime>123456789</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
</xml>*/
	$postObj = simplexml_load_string($postArr);
	//$postObj->TouserName = '';
	//$postObj->FromUserName = '';
	//$postObj->CreateTime = '';
	//$postObj->MsgType = '';
	//$postObj->Event = '';
	//判断该数据包是否是订阅事件推送
	if (strtolower($postObj->MsgType) == 'event') {
		//如果是关注subscribe 事件
		if (strtolower($postObj->Event == 'subscribe')) {
			//回复用户信息
			$toUser = $postObj->FromUserName;
			$fromUser = $postObj->ToUserName;
			$time = time();
			$msgType = 'text';
			//$content = '欢迎关注JackeyEting的微信公众账号:'.$postObj->ToUserName.',\n微信用户的openid'.$postObj->FromUserName.'\n回复消息格式化：'.$tmpstr;
			$content = '欢迎关注JackeyEting的微信公众账号:分别输入1、 2、 3 来看看你家宝贝的心声 (*^__^*) ';
			$template ="
				<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				</xml>
			";
			$info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
			echo $info;
/*<xml>
<ToUserName><![CDATA[toUser]]></ToUserName>
<FromUserName><![CDATA[fromUser]]></FromUserName>
<CreateTime>12345678</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[你好]]></Content>
</xml>*/
	}//end subscribe
	}//end event判断



	//用户仿宋tuwen1关键字的时候，回复一个单图文
	if (strtolower($postObj->MsgType) == 'text'&&trim($postObj->Content)==strtolower('JackeyEting')) {
		$toUser = $postObj->FromUserName;
		$fromUser = $postObj->ToUserName;
		$arr = array(
			array(
				'title'=>'imooc',
				'description'=>'imooc is very cool',
				'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
				'url'=>'http://www.imooc.com'
				),
			array('title'=>'hao123',
				'description'=>'hao123 is very cool',
				'picUrl'=>'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png',
				'url'=>'http://www.hao123.com'
				),
			array('title'=>'qq',
				'description'=>'qq is very cool',
				'picUrl'=>'http://pic.nipic.com/2007-11-14/200711142365843_2.jpg',
				'url'=>'http://www.qq.com'
				),
			);
		$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<ArticleCount>".count($arr)."</ArticleCount>
					<Articles>";
		foreach ($arr as $k => $v) {
			$template.="<item>
					<Title><![CDATA[".$v['title']."]]></Title>
					<Description><![CDATA[".$v['description']."]]></Description>
					<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
					<Url><![CDATA[".$v['url']."]]></Url>
					</item>";
		}
		$template.="</Articles>
					</xml>";
		echo sprintf($template,$toUser,$fromUser,time(),"news");
		//回复文本消息
	}else/* if(strtolower($postObj->MsgType) == 'text')*/ {
				switch ($postObj->Content) {
					case 1:
						$content = '1.你是我的小可爱';
						break;
					case 2:
						$content = '2.我是你的小心肝';
						break;
					case 3:
						$content = '3.I LOEV YOU';
						break;
					case 4:
						$content = "<a href='http://www.imooc.com'>慕课网</a>";
						break;
					default:
						$content = '您好,欢迎关注JackeyEting的微信公众账号';
						break;
				}
				$template = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";		//注意模板的顺序，中括号
				$fromUser = $postObj->ToUserName;
				$toUser = $postObj->FromUserName;
				$time = time();
				//$content = 'imooc is very good';
				$msgType = 'text';
				echo sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
			}//end $postObj->MsgType == 'text'

   }//end responseMsg()
   	//curl的简单实例
   function http_curl(){
   	//获取imooc
   	//1.初始化curl
   	$ch = curl_init();
   	$url = "http://www.imooc.com";
   	//2.设置curl的参数
   	curl_setopt($ch, CURLOPT_URL, $url);
   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   	//3.采集
   	$output = curl_exec($ch);
   	//4.关闭
   	curl_close($ch);
   	var_dump($output);
   }

   function getWxAccessToken(){
   	//1.请求地址
   	$appid = 'wx08ec59d5b53df012';
   	$secret = '5a83854e5869d6d7cc5e5dfedd3fbe4c';
   	$url ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
   	//2.初始化
   	$ch = curl_init();
   	//3.设置参数
   	curl_setopt($ch, CURLOPT_URL, $url);
   	curl_setopt($ch, CURLOPT_HEADER, 0);
   	//如果获取的curl_exec返回值为难，需要添加以下两句，不验证证书
   	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   	//4.调用接口
   	$res = curl_exec($ch);
   	//5.关闭curl
   	curl_close($ch);

  	if (curl_error($ch)) {
   		var_dump(curl_error($ch));
  	 	}

  	 	$arr = json_decode(trim($res),true);
  	 	var_dump($arr);
   }

   function getWxServerIp(){//这里是来获取微信服务的IP。来确认发过来的是微信服务器的
   	$accessToken = "8cmkQtaYsw_3tJqz83fKGYUdA0PJiyOPPmooEbEwhAnPwGhb7ubaKmYag_Ae6tI01QjYaMa4nAKzXuC9GmPec6YK4ie_ycfY77QZs0lL78g2rdCwUD3K_PNS7BhGkgrQSSOjAEAMMI";
   	$url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken;
   	$ch = curl_init();
   	curl_setopt($ch, CURLOPT_URL, $url);
   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   	//如果获取的curl_exec返回值为null，需要添加以下两句，不验证证书
   	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
   	$res = curl_exec($ch);//执行后的返回值
   	if (curl_error($ch)) {
   		var_dump(curl_error($ch));
   	}
   	//json_decode使用true是转成数组，而不是对象
   	$arr = json_decode($res,true);
   	echo "<pre>";
   	var_dump($arr);
   	echo "</pre>";
   }

}//end class



