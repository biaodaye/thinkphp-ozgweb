<?php
namespace app\simple\controller;

class Index extends \app\common\controller\Base {
	
	public function getvcode() {
				
		$verify = new \org\Verify();
		$verify->fontSize = 14;
		$verify->length = 4;
		$verify->useNoise = false;
		$verify->codeSet = '0123456789';
		$verify->imageW = 120;
		$verify->imageH = 30;
		//$verify->expire = 600;
		$verify->entry(1);
		
		return NULL;
	}
	
    public function login() {
		if(cookie("curr_user_name")) {
			//一周内自动登录		
			$name = str_filter(cookie("curr_user_name"));			
			$name = \utility\Encrypt::decode($name);
			
			$wq = [
				"is_admin" => 1
			];
			$user =  D("User")->findByName($name, $wq);					
			unset($user["pwd"]);
			session("user", $user);
			$user["err_login"] = 0;
		
			D("User")->saveData($user, $user["id"]);
		
			header("location:main");
			return NULL;
		}
		
		if(IS_POST) {
			$name = I("post.name", NULL, "str_filter");
			$pwd = I("post.pwd", NULL, "str_filter");		
			
			//提交登录
			$remember = I("post.remember", 0, "intval");
			$vcode = I("post.vcode", "", "str_filter");			
			
			$r = D("User")->adminLogin($name, $pwd, $remember, $vcode);
			\think\Response::type("json");
			return $r;
		}
		
		return $this->fetch("login");
    }	
	
}
