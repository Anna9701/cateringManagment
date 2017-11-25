<div class=top><?php $this->partial("shared/ad_navbar"); ?></div>

<?= $this->tag->form(['session/start']) ?>
    <fieldset>
        <div>
            <label for="login">
                Login
            </label>

            <div>
                <?= $this->tag->textField(['login']) ?>
            </div>
        </div>

        <div>
            <label for="password">
                Password
            </label>

            <div>
                <?= $this->tag->passwordField(['password']) ?>
            </div>
        </div>



        <div>
            <?= $this->tag->submitButton(['Login']) ?>
        </div>
    </fieldset>
<?= $this->tag->endform() ?>