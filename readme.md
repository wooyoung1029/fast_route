# FastRoute - 快速请求路由

##  安装 
### composer 安装
    composer require nikic/fast-route
### composer.json添加自动加载psr-4
       {
           "require": {
               "nikic/fast-route": "^1.3"
           },
           "autoload": {
               "psr-4": {
                   "App\\": "app/"
               }
           }
       }
#### 执行 composer dump-autoload -o 加载文件
    composer dump-autoload -o
  
## 使用
       <?php
       
       require './vendor/autoload.php';
       
       $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
           App\Routes::getRoutes($r);
       });
       
       //$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
       //
       //    $r->addRoute('GET', '/index.php/users/{user_id}',  'UserController.getList');
       //    // {id} must be a number (\d+)
       //    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
       //    // The /{title} suffix is optional
       //    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
       //
       //    $r->addRoute('GET', '/', 'Back/view/IndexController@showIndex');
       //});
       
       // Fetch method and URI from somewhere
       $httpMethod = $_SERVER['REQUEST_METHOD'];
       $uri = $_SERVER['REQUEST_URI'];
       
       // Strip query string (?foo=bar) and decode URI
       if (false !== $pos = strpos($uri, '?')) {
           $uri = substr($uri, 0, $pos);
       }
       $uri = rawurldecode($uri);
       
       $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
       switch ($routeInfo[0]) {
           case FastRoute\Dispatcher::NOT_FOUND:
               // ... 404 Not Found
               break;
           case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
               $allowedMethods = $routeInfo[1];
               // ... 405 Method Not Allowed
               break;
           case FastRoute\Dispatcher::FOUND:
       //        $handler = $routeInfo[1];
       //        $vars = $routeInfo[2];
               // ... call $handler with $vars
               $handler = explode('.', $routeInfo[1]);
               $controller = "App\\Controllers\\" . $handler[0]; // "UserController"
               $action = $handler[1]; // "list" action
               $parameters = $routeInfo[2]; // Action parameters list (e.g. route parameters list)
       
               echo call_user_func_array(
                   [new $controller, $action] // callable
                   , $parameters
               );
       
               break;
       }
