<?php
$redirectStatus = $_SERVER['REDIRECT_STATUS'] ?? '';
if ($redirectStatus === '410') {
    http_response_code(410);
} else {
    http_response_code(404);
}
header('X-Robots-Tag: noindex, follow', true);

$requestedPath = $_SERVER['REDIRECT_URL'] ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestedPath = is_string($requestedPath) ? trim($requestedPath, '/') : '';
$segments = $requestedPath === '' ? [] : array_values(array_filter(explode('/', $requestedPath), 'strlen'));
$firstSegment = strtolower($segments[0] ?? '');

$pageFiles = [
    'ru' => 'index.php',
    'en' => 'en.php',
    'kk' => 'kz.php',
    'kz' => 'kz.php',
    'tr' => 'tr.php',
    'uz' => 'uz.php',
    'pt' => 'pt.php',
    'bn' => 'bd.php',
    'bd' => 'bd.php',
    'id' => 'id.php',
];

$htmlLangMap = [
    'ru' => 'ru',
    'en' => 'en',
    'kk' => 'kk',
    'kz' => 'kk',
    'tr' => 'tr',
    'uz' => 'uz',
    'pt' => 'pt',
    'bn' => 'bn',
    'bd' => 'bn',
    'id' => 'id',
];

$currentLang = array_key_exists($firstSegment, $pageFiles) ? $firstSegment : 'ru';
$htmlLang = $htmlLangMap[$currentLang] ?? 'ru';

$homeHrefMap = [
    'ru' => '/',
    'en' => '/en',
    'kk' => '/kz',
    'kz' => '/kz',
    'tr' => '/tr',
    'uz' => '/uz',
    'pt' => '/pt',
    'bn' => '/bd',
    'bd' => '/bd',
    'id' => '/id',
];
$homeHref = $homeHrefMap[$currentLang] ?? '/';

$texts = [
    'ru' => [
        'title' => '404 - Страница не найдена | DBbet',
        'headline' => 'Страница не найдена',
        'description' => 'Страница, которую вы ищете, не существует или была перемещена.',
        'button' => 'На главную',
    ],
    'en' => [
        'title' => '404 - Page Not Found | DBbet',
        'headline' => 'Page not found',
        'description' => 'The page you are looking for does not exist or has been moved.',
        'button' => 'Back to home',
    ],
    'kk' => [
        'title' => '404 - Бет табылмады | DBbet',
        'headline' => 'Бет табылмады',
        'description' => 'Сіз іздеген бет жоқ немесе басқа мекенжайға ауыстырылған.',
        'button' => 'Басты бетке оралу',
    ],
    'kz' => [
        'title' => '404 - Бет табылмады | DBbet',
        'headline' => 'Бет табылмады',
        'description' => 'Сіз іздеген бет жоқ немесе басқа мекенжайға ауыстырылған.',
        'button' => 'Басты бетке оралу',
    ],
    'tr' => [
        'title' => '404 - Sayfa Bulunamadı | DBbet',
        'headline' => 'Sayfa bulunamadı',
        'description' => 'Aradığınız sayfa mevcut değil veya taşınmış olabilir.',
        'button' => 'Ana sayfaya dön',
    ],
    'uz' => [
        'title' => '404 - Sahifa topilmadi | DBbet',
        'headline' => 'Sahifa topilmadi',
        'description' => 'Siz izlayotgan sahifa mavjud emas yoki boshqa manzilga ko‘chirilgan.',
        'button' => 'Bosh sahifaga qaytish',
    ],
    'pt' => [
        'title' => '404 - Página não encontrada | DBbet',
        'headline' => 'Página não encontrada',
        'description' => 'A página que você procura não existe ou foi movida.',
        'button' => 'Voltar ao início',
    ],
    'bn' => [
        'title' => '404 - পেজ পাওয়া যায়নি | DBbet',
        'headline' => 'পেজ পাওয়া যায়নি',
        'description' => 'আপনি যে পেজটি খুঁজছেন তা নেই বা সরানো হয়েছে।',
        'button' => 'হোমে ফিরুন',
    ],
    'bd' => [
        'title' => '404 - পেজ পাওয়া যায়নি | DBbet',
        'headline' => 'পেজ পাওয়া যায়নি',
        'description' => 'আপনি যে পেজটি খুঁজছেন তা নেই বা সরানো হয়েছে।',
        'button' => 'হোমে ফিরুন',
    ],
    'id' => [
        'title' => '404 - Halaman Tidak Ditemukan | DBbet',
        'headline' => 'Halaman tidak ditemukan',
        'description' => 'Halaman yang Anda cari tidak ada atau telah dipindahkan.',
        'button' => 'Kembali ke beranda',
    ],
];
$t = $texts[$currentLang] ?? $texts['ru'];

function dbbet_404_abs_paths(string $html): string
{
    $html = preg_replace('~\b(src|href)=((["\']))(?!(?:https?:)?//|/|mailto:|tel:|#|data:|javascript:)(?:\./)?~i', '$1=$2/', $html);
    $html = preg_replace('~url\(((["\'])?)(?!(?:https?:)?//|/|data:|#)(?:\./)?~i', 'url($1/', $html);
    return $html;
}

function dbbet_404_render_parts(string $lang, array $pageFiles): array
{
    $file = __DIR__ . '/' . ($pageFiles[$lang] ?? 'index.php');
    if (!is_file($file)) {
        $file = __DIR__ . '/index.php';
    }

    $oldCwd = getcwd();
    chdir(__DIR__);
    ob_start();
    include $file;
    $html = ob_get_clean();
    if ($oldCwd !== false) {
        chdir($oldCwd);
    }

    $styles = '';
    if (preg_match_all('~<style\b[^>]*>.*?</style>~is', $html, $styleMatches)) {
        $styles = implode("\n", $styleMatches[0]);
        $styles = dbbet_404_abs_paths($styles);
    }

    $header = '';
    if (preg_match('~<header\b[^>]*>.*?</header>~is', $html, $headerMatch)) {
        $header = dbbet_404_abs_paths($headerMatch[0]);
    }

    return [$styles, $header];
}

[$siteStyles, $siteHeader] = dbbet_404_render_parts($currentLang, $pageFiles);
?>
<!DOCTYPE html>
<html lang="<?=htmlspecialchars($htmlLang, ENT_QUOTES, 'UTF-8')?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?=htmlspecialchars($t['title'], ENT_QUOTES, 'UTF-8')?></title>
  <meta name="robots" content="noindex, follow">
  <meta name="googlebot" content="noindex, follow">
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="icon" href="/favicon.ico" sizes="any">
  <link rel="icon" type="image/png" sizes="120x120" href="/favicon.png">
  <link rel="icon" type="image/png" sizes="120x120" href="/favicon-120x120.png">
  <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <?=$siteStyles?>
  <link rel="stylesheet" href="/assets/css/main.css?v=111">
  <style>
    html{margin:0!important;padding:0!important;background:#1A1A1A!important;}
    body.dbbet-404-page{margin:0!important;padding:0!important;background:#1A1A1A!important;color:#fff!important;min-height:100vh;overflow-x:hidden;}
    body.dbbet-404-page main{margin:0!important;padding:0!important;background:#1A1A1A!important;min-height:100vh;display:block;}
    body.dbbet-404-page .header{margin:0!important;width:100%!important;}
    body.dbbet-404-page .header a,
    body.dbbet-404-page .header a:hover,
    body.dbbet-404-page .header a:focus{text-decoration:none!important;}
    body.dbbet-404-page .header img{max-width:100%;height:auto;}
    .dbbet-404{background:#fff!important;color:#111!important;width:100%;min-height:calc(100vh - 86px);display:flex;align-items:center;justify-content:center;text-align:center;padding:72px 20px;box-sizing:border-box;}
    .dbbet-404__box{max-width:760px;margin:0 auto;}
    .dbbet-404__code{font-size:clamp(76px,14vw,150px);line-height:.9;font-weight:900;letter-spacing:-.06em;color:#111!important;margin:0 0 24px;}
    .dbbet-404__title{font-size:clamp(30px,5vw,52px);line-height:1.08;font-weight:900;color:#111!important;margin:0 0 18px;}
    .dbbet-404__text{font-size:18px;line-height:1.6;color:#333!important;margin:0 auto 32px;max-width:580px;}
    .dbbet-404__actions{display:flex;align-items:center;justify-content:center;gap:14px;flex-wrap:wrap;}
    .dbbet-404__actions .btn{display:inline-flex;align-items:center;justify-content:center;min-width:190px;text-decoration:none!important;color:#fff!important;}
    @media screen and (max-width:768px){.dbbet-404{padding:56px 16px;min-height:calc(100vh - 68px)}.dbbet-404__text{font-size:16px}.dbbet-404__actions .btn{width:100%;max-width:280px}}
  </style>
</head>
<body class="dbbet-404-page">
  <main>
    <?=$siteHeader?>
    <section class="dbbet-404" aria-labelledby="error-title">
      <div class="dbbet-404__box">
        <div class="dbbet-404__code" aria-hidden="true">404</div>
        <h1 class="dbbet-404__title" id="error-title"><?=htmlspecialchars($t['headline'], ENT_QUOTES, 'UTF-8')?></h1>
        <p class="dbbet-404__text"><?=htmlspecialchars($t['description'], ENT_QUOTES, 'UTF-8')?></p>
        <div class="dbbet-404__actions">
          <a class="btn btn-green dbbet-solid-btn" href="<?=htmlspecialchars($homeHref, ENT_QUOTES, 'UTF-8')?>"><?=htmlspecialchars($t['button'], ENT_QUOTES, 'UTF-8')?></a>
        </div>
      </div>
    </section>
  </main>
  <script src="/assets/js/main.js?v=6" type="module"></script>
  <script src="/assets/js/link.js?v=3"></script>
  <script>
  document.addEventListener('click', function (event) { document.querySelectorAll('.lang-switcher[open]').forEach(function (dropdown) { if (!dropdown.contains(event.target)) { dropdown.removeAttribute('open'); } }); });
  document.addEventListener('keydown', function (event) { if (event.key === 'Escape') { document.querySelectorAll('.lang-switcher[open]').forEach(function (dropdown) { dropdown.removeAttribute('open'); }); } });
  </script>
</body>
</html>
