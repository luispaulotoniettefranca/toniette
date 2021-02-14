<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php if ($seo): echo $seo; endif; ?>
    <title>Toniette</title>
    <link rel="icon" type="image/png" href="<?= CONF_SEO_IMAGE ?>"/>
    <link rel="stylesheet" href="<?= asset("style.min.css") ?>">
    <script src="<?= asset("script.min.js") ?>"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand justify-content-center ml-5" href="<?= url("admin") ?>">
            <img width="30" height="30" class="d-inline-block align-top" src="<?= CONF_SEO_IMAGE ?>" alt="logo">
            TONIETTE
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="<?= url("admin/user") ?>">User</a>
                <a class="nav-item nav-link" href="<?= url("admin/role") ?>">Role</a>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav ml-auto mr-5">
                <a class="nav-item nav-link" href="<?= url("/admin/user/" . session()->user->id) ?>">
                    <?= session()->user->name ?>
                    <? if (session()->user->image && file_exists(CONF_BASE_DIR .
                            DIRECTORY_SEPARATOR . session()->user->image)): ?>
                        <img width="30" height="30" class="ml-1 rounded-circle"
                             src="<?= url(session()->user->image) ?>" alt="UserProfileImage">
                    <? else: ?>
                        <?= icon("person-fill") ?>
                    <? endif; ?>
                </a>
                <a class="nav-item nav-link" href="<?= url("logout") ?>">Logout
                    <?= icon("backspace-reverse-fill") ?></a>
            </div>
        </div>
    </nav>
    <?= $this->section('main') ?>
    <div class="p-2"></div>
    <footer class="fixed-bottom text-center alert-light shadow">
        <p class="mt-4 text-muted">TONIETTE&reg;</p>
    </footer>
</body>
</html>