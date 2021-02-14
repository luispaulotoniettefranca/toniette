<?php $this->layout('__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>


<div style="height: 100vh;" class="container d-flex align-items-center justify-content-center">
    <div class="row text-center">
        <h1 class="col-12"><?= $this->escape($errcode) ?></h1>
        <p class="col-12">
        <? if($message): ?>
            <?= $message ?>
        <? else: ?>
            This page don't exist, or we are having problems in our server,
            or you don't have permission to access this page.
        <? endif; ?>
        </p>
        <p class="col-12"><b>Contact the webmaster: <?= CONF_WEBMASTER_CONTACT ?></b></p>
        <a href="<?= url() ?>"><?= icon("arrow-left-square-fill") ?></a>
    </div>
</div>


<?php $this->stop() ?>