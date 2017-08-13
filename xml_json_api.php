<?php
class Response{
	const XML="xml";
	/*
	按json xml方式输出通信数据
	@param integer $code 状态码
	@param string $message 提示信息
	@param array $data 数据
	@param string $type 数据类型
	@param string $_GET['type'] 获取类型是json 还是xml
	echo string
	*/
	public static function show($code,$message='',$data=array(),$type=self::XML){
		if (!is_numeric($code)) {
			return '';
		}

		$type=isset($_GET['type'])?$_GET['type']:"xml";

		$result = array(
			'code'=>$code,
			'message'=>$message,
			'data'=>$data,
		);
		if ($type=='json'){
			self::json($code,$message,$data);
			exit;
		}elseif ($type=='array') {
			var_dump($result);
		}elseif ($type=='xml') {
			self::xmlEncode($code,$message,$data);
			exit;
		}else{
			//TODO
		}
	}
	/*
	按json 方式输出通信数据
	@param integer $code 状态码
	@param string $message 提示信息
	@param array $data 数据
	return string
	*/
	public static function json($code,$message='',$data=array()){

		if (!is_numeric($code)) {
			return '';
		}

		$result = array(
			'code'=>$code,
			'message'=>$message,
			'data'=>$data
		);

		echo json_encode($result);
		exit;
	}

	/*
	按xml方式输出通信数据
	@param integer $code 状态码
	@param string $message 提示信息
	@param array $data 数据
	return string
	*/
	public static function xmlEncode($code,$message,$data=array()){
		if (!is_numeric($code)) {
			return '';
		}
		$result=array(
			'code'=>$code,
			'message'=>$message,
			'data'=>$data
		);
		header("Content-type:text/xml");
		$xml="<?xml version='1.0' encoding='UTF-8'?>";
		$xml.="<root>";

		$xml.=self::xmlToEncode($result);

		$xml.="</root>";
		echo $xml;
	}

public static function xmlToEncode($data){
	$xml = $attr="";
	foreach($data as $key=>$value){
		if (is_numeric($key)) {
			$attr=" id='{$key}'";
			$key="item";
		}
		$xml.="<{$key}{$attr}>";
		$xml.=is_array($value)?self::xmlToEncode($value):$value;
		$xml.="</{$key}>";
	}
	return $xml;exit;
}
}//class
/*
$data=array(
	'id'=>1,
	'name'=>'Jackey',
	'type'=>array(4,5,6),
	'test'=>array(1,45,67=>array(123,'Eting')),
);
Response::show(200,'success',$data);
//<0>4</0> <item id='0'>4</item>
*/