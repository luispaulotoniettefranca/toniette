<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>


<div class="container text-center mt-5 justify-content-center">
    <? if ($user->image): ?>
        <img width="72" height="72" class="ml-1 rounded-circle"
             src="<?= url($user->image) ?>" alt="UserProfileImage">
    <? else: ?>
        <img class="mb-4" src="<?= CONF_SEO_IMAGE ?>" alt="" width="72" height="72">
    <? endif; ?>
    <hr>
    <p>Username: <b><?= $user->name ?></b></p>
    <p>Email address: <b><?= $user->email ?></b></p>
    <p>Role: <b><?= $user->role() ?></b></p>
    <hr>
</div>


<?php $this->stop() ?>
