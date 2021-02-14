<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>


<div style="height: 80vh;" class="container d-flex align-items-center justify-content-center">
   <h1>WELCOME, <?= session()->user->name ?>!</h1>
</div>


<?php $this->stop() ?>