<div class=top><?php $this->partial("shared/ad_navbar"); ?></div>

<div class="row">
    <div class="col-md-6">
            <div class="page-header">
                <h2>Log In</h2>
            </div>
    {{ form("session/start") }}
        <fieldset>
            <div class="form-group">
                <label for="login">
                    Login
                </label>

                <div class="controls">
                    {{ text_field("login") }}
                </div>
            </div>

            <div class="form-group">
                <label for="password">
                    Password
                </label>

                <div class="controls">
                    {{ password_field("password") }}
                </div>
            </div>

            <div class="form-group">
                {{ submit_button("Login") }}
            </div>
        </fieldset>
    {{ endForm() }}
    </div>
</div>