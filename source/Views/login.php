<?php $this->layout('__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>


<div style="height: 100vh;" class="container d-flex align-items-center justify-content-center">
    <form class="text-center" method="post" action="<?= url("/authenticate") ?>">
        <img class="mb-4" src="<?= CONF_SEO_IMAGE ?>" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Hello! Whats up?</h1>
        <div class="form-group">
            <label for="email" class="sr-only">Email address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>
        </div>
        <div class="form-group">
            <label for="password" class="sr-only">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">TONIETTE&reg;</p>
    </form>
</div>


<?php $this->stop() ?>