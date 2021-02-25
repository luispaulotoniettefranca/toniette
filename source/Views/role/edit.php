<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>


    <h2 class="text-center mt-2 mb-4">ROLE EDIT</h2>
    <div class="container d-flex justify-content-center">
        <hr>
        <form action="<?= url("admin/role/{$role->id}/update") ?>" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" required
                value="<?= $role->name ?>">
            </div>
            <hr>
            <label for="permissions">Role permissions</label>
            <select name="permissions[]" id="permissions" multiple>
                <?php foreach ($permissions as $p): ?>
                <option value="<?= $p->id ?>"<?php if(in_array($p,$authorized)):?> selected <?php endif;?>>
                    <?= $p->route ?>
                </option>
                <?php endforeach; ?>
            </select>
            <hr>
            <div class="mt-4 form-group text-center">
                <button class="btn btn-primary" type="submit">UPDATE</button>
            </div>
        </form>
        <hr>
    </div>

    <script>
        $('#permissions').multiSelect({
            selectableHeader: "<small>Unauthorized</small>",
            selectionHeader: "<small>Authorized</small>"
        });
    </script>


<?php $this->stop() ?>