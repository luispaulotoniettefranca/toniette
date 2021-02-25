<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php if ($seo): ?>
    <?= $seo ?>
    <?php endif; ?>
    <title>Toniette</title>
    <link rel="icon" type="image/png" href="<?= CONF_SEO_IMAGE ?>"/>
    <link rel="stylesheet" href="<?= asset("style.min.css") ?>">
    <script src="<?= asset("script.min.js") ?>"></script>
</head>
<body>
<?= $this->section('main') ?>
</body>
</html>