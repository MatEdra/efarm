<?php
// Dynamically resolve the app root URL, works on localhost (/efarm/) and Railway (/)
$_pwa_root = realpath(dirname(__FILE__));
$_doc_root = realpath($_SERVER['DOCUMENT_ROOT']);
$_rel = str_replace('\\', '/', str_replace($_doc_root, '', $_pwa_root));
$_pwa_base = rtrim($_rel, '/') . '/';
if ($_pwa_base === '/') $_pwa_base = '/';
?>
    <!-- PWA -->
    <link rel="manifest" href="<?= $_pwa_base ?>manifest.php">
    <meta name="theme-color" content="#2e7d32">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="eFarm">
    <link rel="apple-touch-icon" href="<?= $_pwa_base ?>icons/icon.svg">
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('<?= $_pwa_base ?>sw.js', { scope: '<?= $_pwa_base ?>' })
                .catch(function (e) { console.warn('SW:', e); });
        });
    }
    </script>
