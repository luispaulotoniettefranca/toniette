<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>

    <h2 class="text-center mt-2 mb-4">USER EDIT</h2>
    <div class="container justify-content-center">
        <hr>
        <form action="<?= url("admin/user/{$user->id}/update") ?>" method="post" enctype="multipart/form-data">
            <div class="text-right">
                <a href="<?= url("admin/user/{$user->id}/password/edit") ?>">PASSWORD RESET</a>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" required
                value="<?= $user->name ?>">
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input class="form-control" type="email" name="email" id="email" required
                value="<?= $user->email ?>">
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" name="role" id="role">
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->id ?>"<?php if ($role->id == $user->role): ?> selected <?php endif; ?>>
                        <?= $role->name ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if ($user->image ): ?>
            <img src="<?= url($user->image) ?>" alt="profile" width="72" height="72">
            <?php endif; ?>
            <div class="form-group">
                <label for="image">Profile Image</label>
                <input class="form-control-file" type="file" name="image" id="image">
                <small>Ideal size: 400px X 400px</small>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-4">
                    <div class="form-group">
                        <label for="authorized">Authorize resource access</label>
                        <select name="authorized[]" id="authorized" multiple>
                            <option value="0" selected disabled>AUTHORIZED ROUTES</option>
                            <?php foreach ($permissions as $p): ?>
                                <option value="<?= $p->id ?>"
                                    <?php if(in_array($p->id,$authorized)): ?> selected<?php endif; ?>>
                                    <?= $p->route ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-4">
                    <div>
                        <label for="unauthorized">Unauthorize resource access</label>
                        <select name="unauthorized[]" id="unauthorized" multiple>
                            <option value="0" selected disabled>UNAUTHORIZED ROUTES</option>
                            <?php foreach ($permissions as $p): ?>
                                <option value="<?= $p->id ?>"
                                    <?php if(in_array($p->id,$unauthorized)): ?> selected<?php endif; ?>>
                                    <?= $p->route ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group text-center mb-5">
                <button class="btn btn-primary" type="submit">UPDATE</button>
            </div>
        </form>
    </div>

    <script>
        $('#authorized').multiSelect({
            selectableHeader: "<small>Routes</small>",
            selectionHeader: "<small>Authorized</small>"
        });
    </script>

    <script>
        $('#unauthorized').multiSelect({
            selectableHeader: "<small>Routes</small>",
            selectionHeader: "<small>Unauthorized</small>"
        });
    </script>

<?php $this->stop() ?>