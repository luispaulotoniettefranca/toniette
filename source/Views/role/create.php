<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>


    <h4 class="text-center mt-2 mb-4">NEW ROLE</h4>
    <div class="container d-flex justify-content-center">
        <hr>
        <form action="<?= url("admin/role") ?>" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" required
                       value="">
            </div>
            <hr>
            <label for="permissions">Role permissions</label>
            <select name="permissions[]" id="permissions" multiple>
                <? foreach ($permissions as $p): ?>
                    <option value="<?= $p->id ?>">
                        <?= $p->route ?>
                    </option>
                <? endforeach; ?>
            </select>
            <hr>
            <div class="mt-4 form-group text-center">
                <button class="btn btn-primary" type="submit">CREATE</button>
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