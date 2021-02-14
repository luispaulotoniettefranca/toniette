<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>


    <style>
        .bi-plus {
            fill: white;
        }
        .bi-pencil {
            fill: green;
        }
        .bi-x-circle {
            fill: red;
        }
    </style>
    <div class="container mt-5 mb-2">
        <a href="<?= url("admin/user/create") ?>">
            <button class="btn btn-primary">
                NEW USER <?= icon("plus") ?>
            </button>
        </a>
    </div>
    <div class="container d-flex justify-content-center">
        <table class="table table-bordered table-striped text-center">
            <thead>
                <th>ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </thead>
            <tbody>
            <? foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= $user->name ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->role() ?></td>
                    <td>
                        <div class="row">
                            <div class="col-6 border-right">
                                <a href="<?= url("admin/user/{$user->id}/edit") ?>">
                                    <?= icon("pencil") ?>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?= url("admin/user/{$user->id}/destroy") ?>">
                                    <?= icon("x-circle") ?>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    </div>


<?php $this->stop() ?>