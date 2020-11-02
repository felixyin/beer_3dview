#说明文档
### 辅助数据库
```text
需要安装数据表 im.sql
```
### 获取ticket
```php
$ticketarr = hook('get_ticket'); //无需参数
```
### 注册
##### 注册事件(event)类型参数
```php
 $data = [
        'modeType'   => 'add',    //不填默认 add, 处理方法 add(添加) del(删除)
        'attach'     => [],       //选填, 非必要就不要添加此参数, 后台注册与用户自定义同名以后台为准
        'eventName'  => 'T-1',    //必填, 事件名称
        'token'      => '9ecf7da34b2e388340f7cef25c979f1d4f57db4d',  //必填, 注册体
        'notifyUrl'  => 'http://115.29.140.95/message/index/notify', //必填, 回调地址
];  
// 回调地址 为逻辑代码编写区,请者正确填写
// 同一个uid,gids,token 二次提交为修改成功
```
返回参数
```json
{
    "code": 0,                    // 返回状态码 0为成功
    "status": "success",          // success(成功) | fail(失败)
    "msg": "事件修改成功",          // 请求返回说明
    "info": {                     // 返回请求信息
        "eventNames": [           // 注册事件所属组
            "T-1"
        ],
        "serviceType": "event"    // 用户注册类型 event事件    
    },
    "time": 1595574955            // 返回时间     
}
```
##### 注册聊天(chat)类型参数 
```php
 $data = [
        'attach'     => [],    //选填, 非必要就不要添加此参数, 后台注册与用户自定义同名以后台为准
        'modeType'   => 'add', //不填默认 add, 处理方法 add(添加) del(删除)
        'uid'        => '123', //必填, 注册人id或者用户账号登录token
        'gids'       => $gids[]['gid'], //必填, 注册人所属分组
        'token'      => '98f90cbc38061ae85e9bdac341a597344f57db41',  //必填, 注册体
        'notifyUrl'  => 'http://115.29.140.95/message/index/notify', //必填, 回调地址
];  
// 同一个uid,gids,token 二次提交为修改成功
```
返回参数
```json
{
    "code": 0,                // 返回状态码 0为成功             
    "status": "success",      // success(成功) | fail(失败)    
    "msg": "注册成功",          // 请求返回说明       
    "info": {                 // 返回请求信息
        "uid": "123456",      // 注册用户id
        "gids": [             // 注册用户所属组
            "A"
        ],
        "serviceType": "chat"  // 用户注册类型 chat 聊天类型        
    },
    "time": 1595572538         // 返回时间     
}
```
### 事件
##### 消息发送
```php
$data = [
        'serviceType'=> 'event',             //必填, 服务类型 event(事件类型)
        'modeType'   => 'send',              //不填默认 add, 处理方法 add(添加) del(删除)
        'eventName'  => 'T-1',               //必填, 事件名称
        'toUser'    =>[],                    //必填, 到用户注册IM的token
        'data'      =>array('name'=>'LSQ'),  //必填, 发送的信息 自定义数组    
        'notifyUrl'  => 'http://115.29.140.95/message/index/notify', //必填, 回调地址
];  
```
返回参数
```json
{
    "code": 0,                 // 返回状态码 0为成功
    "status": "success",       // success(成功) | fail(失败)
    "msg": "事件已经发送！",      // 请求返回说明
    "info": {                   // 返回请求信息
        "num": 1,               // 发送目标个数
        "serviceType": "event"  // 用户注册类型 chat 聊天类型        
    },
    "time": 1595575840          // 返回时间     
}
```
给客户端发送的参数
```json
{
    "code": 0,                 // 返回状态码 0为成功
    "status": "success",       // success(成功) | fail(失败)
    "msg": "ok",               // 请求返回说明
    "info": {                  // 返回请求信息
      "name": "LSQ"            // 返回参数
    },
    "time": 1595575840         // 返回时间     
}
```
##### 下线通知
```text
通知注册时的回调地址
```
```json
{
    "code" : 0,                                               // 返回状态码 0为成功
    "status" : "success",                                     // success(成功) | fail(失败)
    "msg" : "ok",                                             // 请求返回说明
    "info" : {                                                // 返回请求信息
        "serviceType" : "colse",                              // 下线通知
        "token" : "9ecf7da34b2e388340f7cef25c979f1d4f57db4d", // 下线用户通讯token
        "eventName" : [                                       // 事件属组
                "T-1"
          ],
        "randomStr": "2y4zV5uhIG6fFZrjbR",                    // 加密字符串
        "sign" : "e9586bd4886b3de4201c990fea048fd0",          // 验证字符串
    }

    "time" : 1595577575         // 返回时间     
}
```
### 计划任务
参数说明
 ```php
    $data = [
        'cronType'   => 1,     //1.间隔时间,次数;2.指定开始时间,间隔时间,次数;3.指定开始时间一次任务;
        'cronName'   => 'j-3', //必填, 计划任务名称
        'data'       => [],    //不填默认为为空,回调参数,后台注册与用户自定义同名以后台为准
        'modeType'   => 'add', //不填默认 add, 处理方法 add(添加) del(删除)
        'runNum'     => 2,     //当cronType为1和2时 必填, 执行次数    
        'notifyUrl'  => 'http://115.29.140.95/message/index/notify', //必填,回调地址
        'start_time' => 1594988027,//当cronType为2时 必填, 开始执行的时间的时间戳
        'cronTime'   => 1594988027 //必填, cronType为1和2时为时间间隔(例: 10 则为十秒钟执行一次) ,cronType为3时为指定时间戳(例: 1594988027 则2020-07-17 20:13:47 执行一次)       
    ];
 ```
计划任务返回结果说明
```text
返回的 randomStr 和 配置的 key 加密后 与 sign 比较,相同则OK,不同则来源不对不予处理
```
返回结果
```json
{
    "code" : 0,                                         // 返回状态码 0为成功       
    "status" : "success",                               // success(成功) | fail(失败)    
    "msg" : "ok",                                       // 请求返回说明       
    "info" : {                                          // 返回请求信息
            "uid" : 3,                                  // 定义字段
            "name" : "LSQ",                             // 定义字段
            "serviceType" : "cron",                     // 服务类型 计划任务
            "randomStr" : "hTaBLwAguKEV9RCnYJ",         // 加密字段
            "sign" : "8200f283492d00910f24d9b0542f5e1f" // 验证字段
    },
    "time" : 1595036112,                                // 返回时间     
}
```

