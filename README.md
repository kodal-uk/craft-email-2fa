# Email Two-Factor Authentication plugin for Craft CMS 3.x

Email based Two-factor authentication plugin for Craft CMS

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require kodal/craft-email-2fa

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Email 2FA.

## Restricting access to pages.

Include `{{ requireTwoFactorLogin() }}` in the template for pages you want to require two-factor authentication. 
Users will be redirected to the verify page link set in the plugin settings. 

`{{ requireTwoFactorLogin() }}` also calls requireLogin so logged out users will be redirected to the login form and logged in users will be redirected to the verify form.

See Craft CMS documentation for login form example https://docs.craftcms.com/v3/dev/examples/login-form.html

## Verify email form example.

```
<form method="post" accept-charset="UTF-8">
    {{ csrfInput() }}
    {{ actionInput('email-2fa/verify') }}
    <div>
        <label for="verifyCode">{{ 'Verification Code' | t }}</label>
        <div>
            {% for i in 1..craft.email2fa.verifyCodeLength %}
                <input type="number" min="0" max="9" name="verifyCode[]">
            {% endfor %}
        </div>
    </div>
    <div>
        <input type="submit" value="{{ 'Login' | t }}">
    </div>
</form>
```

## Resend email form example.

Optionally add a resend email link.

```
<form method="post" accept-charset="UTF-8">
    {{ csrfInput() }}
    {{ actionInput('email-2fa/verify/resend') }}
    <div>
        <input type="submit" value="{{ 'Resend verify email' | t }}">
    </div>
</form>
```

Brought to you by [Kodal](https://www.kodal.uk/)
