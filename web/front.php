<?php
	require_once __DIR__.('../../vendor/autoload.php');

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing;

	$request = Request::createFromGlobals();
	$routes = include('../src/app.php');

	$context = new Routing\RequestContext();
	$context->fromRequest($request);
	$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

	try {
		extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
		ob_start();
		include sprintf('../src/pages/%s.php', $_route);

		$response = new Response(ob_get_clean());
	} catch (Routing\Exception\ResourceNotFoundExecption $exception) {
		$response = new Response('Not Found', 404);
	} catch (Exception $exception) {
		$response = new Response('An error occured', 500);
	}

	$response->send();
?>