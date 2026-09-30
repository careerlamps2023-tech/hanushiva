<?php
/**
 * 404 handler.
 *
 * ErrorDocument only accepts a path from the SERVER root, which breaks whenever
 * the site sits in a subfolder (localhost/hanushiva.com/...). A rewrite to this
 * file is relative, so it works in a subfolder and at the domain root without
 * being edited on deploy — and unlike a plain internal rewrite to 404.html it
 * still sends a real 404 status rather than a "soft 404" 200.
 */
http_response_code(404);
header('Content-Type: text/html; charset=utf-8');
readfile(__DIR__ . '/404.html');
