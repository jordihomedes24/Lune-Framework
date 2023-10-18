<?php

require_once "../vendor/autoload.php";

use Lune\App;
use Lune\Http\Middleware;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Route;
use Lune\Validation\Rules\Required;

$app = App::bootstrap();

$app->router->get('/test/{param}', function (Request $request) {        
    return json($request->routeParameters());
});

$app->router->post('/test', function (Request $request) {
    return Response::text("POST OK");
});

$app->router->get('/redirect', function (Request $request) {
    return redirect("/test");
});

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if ($request->headers('Authorization') != 'test') {
            return json(["Message" => "Not authenticated"])->setStatus(404);
        }

        $response = $next($request);
        $response->setHeader('X-Test-Custom-Header', 'Test');

        return $response;
    }
}

Route::get('/html', fn (Request $request) => view('home', [ 'user' => 'Jordi' ]));

Route::get('/middlewares', fn (Request $request) => json(["message" => "Hello"]))
    ->setMiddlewares([AuthMiddleware::class]);

Route::post('/validate', fn (Request $request) => json($request->validate([
    'email' => [ 'required_with:num' ],
    'num' => [ 'number' ]
], [
    'email' => [
        'email' => "HEY YOU THIS FIELD HAS TO BE AN EMAIL"
    ]
])));

$app->run();
