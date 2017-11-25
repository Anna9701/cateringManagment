<div class=top><?php $this->partial("shared/ad_navbar"); ?></div>

{{ form("session/start") }}
    <fieldset>
        <div>
            <label for="login">
                Login
            </label>

            <div>
                {{ text_field("login") }}
            </div>
        </div>

        <div>
            <label for="password">
                Password
            </label>

            <div>
                {{ password_field("password") }}
            </div>
        </div>



        <div>
            {{ submit_button("Login") }}
        </div>
    </fieldset>
{{ endForm() }}