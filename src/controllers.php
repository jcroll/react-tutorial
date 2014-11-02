<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function (Request $request) use ($app) {

    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$api = $app['controllers_factory'];
$api->get('/comments', function (Request $request) use($app) {
    $session = $app['session'];
    if (!$session->has('comments')) {
        $initialComments = [
            ['author' => 'Pete Hunt',    'text' => 'This is one comment'],
            ['author' => 'Jordan Walke', 'text' => 'This is *another* comment']
        ];
        $session->set('comments', $initialComments);
    }

    $comments = $session->get('comments');

    if ('POST' === $request->getMethod()) {
        if ($comment = $request->request->get('comment')) {
            $comments[] = $comment;
            $session->set('comments', $comments);
        }
    }

    return new JsonResponse($comments);
})
->bind('api_comments')
;

$app->mount('/api', $api);

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
