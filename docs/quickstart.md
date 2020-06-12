# Quick start guide

Follow this guide if you want to trigger a deploy hook, for example on Netlify or similar services.
For more complex use cases, check out the [complete configuration docs](https://github.com/pju-/kirby-webhook-field/tree/master/docs/config.md).

## 1 - Install

[Check the readme](https://github.com/pju-/kirby-webhook-field/tree/master/docs/config.md) for installation options.

## 2 - Set General Options

You will need to provide some base configuration for you Kirby installation in `/site/config/config.php`.

[Learn more about config.php](https://getkirby.com/docs/guide/configuration)

Example:
```php
'pju.webhook-field.hooks' => [
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
    type: webhook
    label: Deploy on Netlify
```

If you want multiple hooks, for example `live` and `staging`, you can also reference the name of any hook you have set in your ```/site/config/config.php``` file with `name: HOOK_NAME`.

In the example above, you would use `name: netlify_deploy`.

If you leave the ```hook``` option empty instead, it will default to the first hook you have configured.

## 4 - Example configuration for Netlify

Add two deploy notifications on Netlify:

- Outgoing webhook: **Deploy succeeded**

  URL: `https://www.yoursite.com/webhook/netlify_deploy/success`
- Outgoing webhook: **Deploy failed**

  URL: `https://www.yoursite.com/webhook/netlify_deploy/error`

If you have changed the name of the hook, replace `netlify_deploy` with the name you chose.

A POST request to these URLs will update the status of the webhook. If you want, you can protect them by changing `webhook` to a secret parameter.
[Learn how use a custom name for your endpoints](https://github.com/pju-/kirby-webhook-field/tree/master/docs/config.md#endpoint).

The use of a JWS secret is currently not supported. Use a secret key in the route name instead.

## 5 - Done

You should now see the field in the panel of your Kirby installation.
If there are any problems, check the developer tools JS console for debugging (Make sure you have not disabled the `debug` flag in the field settings).

If you want to customize anything, [check out the full options guide](https://github.com/pju-/kirby-webhook-field/tree/master/docs/config.md).
