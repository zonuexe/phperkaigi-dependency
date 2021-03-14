<?php

namespace zonuexe\PHPerKaigi2021;

require_once __DIR__ . '/functions.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/now') {
    header('Content-Type: application/json');
    echo json_encode(api_now(time()));
    exit;
} elseif (preg_match('@add/(?<x>[\d+])/(?<y>[\d+])@u',
                     $uri, $matches)) {
    ['x' => $x, 'y' => $y] = $matches;
    header('Content-Type: application/json');
    echo json_encode(api_add($x, $y));
    exit;
}

http_response_code(404);
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<title>Not Found</title>
<h1>API Not Found</h1>
