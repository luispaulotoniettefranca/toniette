<?php $this->layout('admin/__theme', ["seo" => $seo]) ?>
<?php $this->start('main') ?>

    <h2 class="text-center mt-5 mb-4">PASSWORD RESET</h2>
    <h6 class="text-center"><?= $user->name ?></h6>
    <div class="container justify-content-center">
        <hr>
        <form action="<?= url("admin/user/{$user->id}/password/update") ?>" method="post">
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" type="password" name="password" id="password" required
                       minlength="8" maxlength="40">
            </div>
            <div class="form-group">
                <label for="repeat">Repeat Password</label>
                <input class="form-control" type="password" id="repeat" required
                       minlength="8" maxlength="40">
            </div>
            <div class="text-center d-flex" id="status"></div>
            <div class="form-group text-center">
                <button id="submit" disabled class="btn btn-primary" type="submit">UPDATE</button>
            </div>
        </form>
    </div>

    <script>
        const success = `<h6 class="p-2 alert-success rounded">PASSWORDS MATCH</h6>`;
        const error = `<h6 class="p-2 alert-danger rounded">PASSWORDS DON'T MATCH</h6>`;
        $("#password").on("keyup", () => {
            if ($("#password").val() !== $("#repeat").val()) {
                $("#submit").attr("disabled", "disabled");
                $("#status").html(error);
            } else {
                $("#submit").attr("disabled", false);
                $("#status").html(success);
            }
        });
        $("#repeat").on("keyup", () => {
            if ($("#password").val() !== $("#repeat").val()) {
                $("#submit").attr("disabled", "disabled");
                $("#status").html(error);
            } else {
                $("#submit").attr("disabled", false);
                $("#status").html(success);
            }
        });
    </script>

<?php $this->stop() ?>