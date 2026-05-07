<?php
header('Content-Type: application/manifest+json');
header('Cache-Control: no-cache');

$doc_root = realpath($_SERVER['DOCUMENT_ROOT']);
$app_dir  = realpath(dirname(__FILE__));
$rel      = str_replace('\\', '/', str_replace($doc_root, '', $app_dir));
$base     = rtrim($rel, '/') . '/';
if ($base === '/') $base = '/';

echo json_encode([
    'name'             => 'Smart Farming Philippines',
    'short_name'       => 'eFarm',
    'description'      => 'Agricultural management platform for Filipino farmers',
    'start_url'        => $base . 'index.php',
    'scope'            => $base,
    'display'          => 'standalone',
    'orientation'      => 'portrait-primary',
    'background_color' => '#2e7d32',
    'theme_color'      => '#2e7d32',
    'categories'       => ['productivity', 'lifestyle'],
    'icons'            => [
        [
            'src'     => $base . 'icons/icon.svg',
            'sizes'   => 'any',
            'type'    => 'image/svg+xml',
            'purpose' => 'any',
        ],
        [
            'src'     => $base . 'icons/icon-maskable.svg',
            'sizes'   => 'any',
            'type'    => 'image/svg+xml',
            'purpose' => 'maskable',
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
