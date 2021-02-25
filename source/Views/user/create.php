<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>

    <h4 class="text-center mt-2 mb-4">NEW USER</h4>
    <div class="container justify-content-center">
        <hr>
        <form action="<?= url("admin/user/") ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input class="form-control" type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" type="password" name="password" id="password" required
                       minlength="8" maxlength="40">
            </div>
            <div class="form-group">
                <label for="image">Profile Image</label>
                <input class="form-control-file" type="file" name="image" id="image">
                <small>Ideal size: 400px X 400px</small>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" name="role" id="role">
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->id ?>"><?= $role->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary" type="submit">CREATE</button>
            </div>
        </form>
    </div>


<?php $this->stop() ?>