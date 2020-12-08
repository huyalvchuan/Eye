
# Eye
## 特性：  
单文件入口，自定义路由，轻量orm，websocket功能，邮件，微信，netclient插件，json与object与数组互换的请求方式，日志文件，自定义插件功能
## Eye 自动代码生成工具
https://github.com/huyalvchuan/Eye-GenerateTool.git      
1.路由方式：localhost/project/index.php/controller/func  
2.restful风格，$this->I("json")获取请求数据，已经进行过数据过滤放心使用  
3.返回数据：$this->response(code,msg,data)  
4.定义before，after钩子函数操作  
5.$this->mylog("msg")日志操作。  
## 工程目录结构  
-Core  
--config.php    //配置文件  
--Controller    //路由和具体业务代码  
--Func          //自定义插件  
--File          //上传文件存放  
--Service       //业务代码  
--View          //视图文件  
--ViewCache     //视图文件编译缓存  
-Eye            //核心代码不允许修改  
-index.php      //工程入口  
-log.txt        //日志文件  
-pom.php        //工程配置  
-socketindex.php//websocket入口  
-View  
-Template  
    
## 入门指南：  
如果我要定义一个api接口为：localhost/index.php/user/login  
```
class userController extends Eye {  
		function login () {   
			$request = $this->I("json");    //获取json数据  
			$code = $request->code;     
			$db=$this->M(); //获取数据库操作实例  
		}  
}
```
文件命名为：userController.php 不符合命名规范不过这是自己定义的规范
获取数据 $this->I("json")获取json数据 预处理：跨域，拦截空操作
$this->response(状态码，msg，返回的数据["user"=>[]])
数据库ORM操作：https://github.com/huyalvchuan/Eye/wiki/Eye
MVC结构：
类Smarty
赋值：$this->Assign($key, $value)
显示模板：$this->Display(模板名字) 模板放在View里面，命名规则：名字.html   具体的操作请参照Smarty的模板规则

### 插件功能：
$this->doaction(插件名)获取插件实例 
已经有的插件： 
邮件：mailer 
微信接口：wx 
易班开放接口：yiban 
curl接口：netclient 
具体的方法请看源码嘿嘿 
token机制： 
Eye整体已经不再使用session这个玩意所以保存用户信息就使用了token机制 
用户登录的时候会颁发给用户token字符串，用户以后请求的时候就用token代表用户的id 
参考 ：
userController.php loginService 
$this->token($this, $db, $token)返回用户id //db是数据库实例，因为为了方便测试开发，直接将token和用户绑定到了数据库里，redis扩展请移步boot框架。
文件命名为：userController.php 不符合命名规范不过这是自己定义的规范  
获取数据 $this->I("json")获取json数据 预处理：跨域，拦截空操作  
$this->response(状态码，msg，返回的数据["user"=>[]])  
数据库ORM操作：https://github.com/huyalvchuan/Eye/wiki/Eye  
MVC结构：  
类Smarty  
赋值：$this->Assign($key, $value)  
显示模板：$this->Display(模板名字) 模板放在View里面，命名规则：名字.html   具体的操作请参照Smarty的模板规则  
   
###  插件功能：  
$this->doaction(插件名)获取插件实例  
