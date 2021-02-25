<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>

<div class="container text-center mt-5 justify-content-center">
    <img class="mb-4" src="<?= CONF_SEO_IMAGE ?>" alt="" width="72" height="72">
    <hr>
    <p>Role: <b><?= $role->name ?></b></p>
    <hr>
    <table class="table table-bordered table-striped">
        <thead>
        <th>ID</th>
        <th>Route</th>
        <th>Method</th>
        <th>Handler</th>
        <th>Action</th>
        </thead>
        <tbody>
        <?php foreach ($permissions as $permission): ?>
        <tr>
            <td><?= $permission->id ?></td>
            <td><?= $permission->route ?></td>
            <td><?= $permission->method ?></td>
            <td><?= $permission->handler ?></td>
            <td><?= $permission->action ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php $this->stop(); ?>
