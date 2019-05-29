# Quick start guide

Follow this guide if you want to trigger a deploy hook, for example on Netlify.
For more complex use cases, check out the [complete configuration docs](https://github.com/pju-/kirby-webhooks/tree/master/docs/config.md).

## 1 - Install

Download the plugin and install it to ```/site/plugins/webhooks``` or [install as a git submodule](https://getkirby.com/docs/guide/plugins/plugin-setup-basic#the-three-plugin-installation-methods).

## 2 - Set General Options

You will need to provide some base configuration for you Kirby installation in `/site/config/config.php`.

[Learn more about config.php](https://getkirby.com/docs/guide/configuration)

Example:
```php
'pju.webhooks.hooks' => [
    'netlify_deploy' => [
        'url' => 'https://api.netlify.com/build_hooks/YOUR_BUILD_HOOK'
    ]
]
```

## 3 - Use Field (in Kirby blueprints)
 
Place on the blueprint of any page where you want to show it.

Example:
```yml
fields:
  deploy:
    type: webhooks
    label: Deploy on Netlify
```

If you want multiple hooks, for example `live` and `staging`, you can also reference the name of any hook you have set in your ```/site/config/config.php``` file with `name: HOOK_NAME`.

In the example above, you would use `name: netlify_deploy`.

If you leave the ```hook``` option empty instead, it will default to the first hook you have configured.

## 4 - Example configuration for Netlify

Add two deploy notifications on Netlify:

- Outgoing webhook: **Deploy succeeded**

  URL: `https://www.yoursite.com/webhooks/netlify_deploy/success`
- Outgoing webhook: **Deploy failed**

  URL: `https://www.yoursite.com/webhooks/netlify_deploy/error`

If you have changed the name of the hook, replace `netlify_deploy` with the name you chose.

A POST request to these URLs will update the status of the webhook. If you want, you can protect them by changing `webhooks` to a secret parameter. 
[Learn how use a custom name for your endpoints](https://github.com/pju-/kirby-webhooks/tree/master/docs/config.md#endpoint).

The use of a JWS secret is currently not supported. Use a secret key in the route name instead.

## 5 - Done

You should now see the field the panel for your Kirby installation.
It should work like this:

If there are any problems, check the developer tools JS console for debugging.

If you want to customize anything, [check out the full options guide](https://github.com/pju-/kirby-webhooks/tree/master/docs/config.md).
