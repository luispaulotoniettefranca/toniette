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
        <a href="<?= url("admin/role/create") ?>">
            <button class="btn btn-primary">
                NEW ROLE <?= icon("plus") ?>
            </button>
        </a>
    </div>
    <div class="container d-flex justify-content-center">
        <table class="table table-bordered table-striped text-center">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </thead>
            <tbody>
            <? if ($roles): ?>
            <? foreach ($roles as $role): ?>
                <tr>
                    <td><?= $role->id ?></td>
                    <td><?= $role->name ?></td>
                    <td>
                        <div class="row">
                            <? if ($role->id != session()->user->role || session()->user->role == 1): ?>
                            <div class="col-6 border-right">
                                <a href="<?= url("admin/role/{$role->id}/edit") ?>">
                                    <?= icon("pencil") ?>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?= url("admin/role/{$role->id}/destroy") ?>">
                                    <?= icon("x-circle") ?>
                                </a>
                            </div>
                            <? else: ?>
                                <div class="col-12">
                                    <small><b>You can't manipulate your own role</b></small>
                                </div>
                            <? endif; ?>
                        </div>
                    </td>
                </tr>
            <? endforeach; ?>
            <? else: ?>
                <tr><td colspan="3"><h4>EDITABLE ROLES NOT FOUND!</h4></td></tr>
            <? endif; ?>
            </tbody>
        </table>
    </div>


<?php $this->stop() ?>