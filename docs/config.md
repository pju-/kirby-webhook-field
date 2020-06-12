# General config
You can set the general options for the plugin in your Kirby site's `config.php`.
The following options are available:

Name | Type | Default | Description
--- | --- | --- | ---
endpoint | `String` | `'webhook'` | The endpoint for incoming webhook (see [endpoint docs](#endpoint)).
hooks | `Array` | `[]` | An array of hooks that you want to be able to trigger. Each entry consists of a structured array of webhook options (see [hook structure](#hook-structure))
labels | `Array` | [see "Labels"](#labels) | An array of label names or translations.

## Endpoint
Defines the route that will be used to update the status of hooks. Functionally this provides a webhook that reacts to incoming requests.

**I strongly recommend using this option as a way to protect the API from unwanted access.**
You can use a secret key in the URL to protect public access to the status update.

The endpoints for updating the status to success/error will be<br>
`https://www.yoursite.com/ENDPOINT/HOOK_NAME/success`<br>
and<br>
`https://www.yoursite.com/ENDPOINT/HOOK_NAME/error`<br>
where ENDPOINT is the configured endpoint and HOOK_NAME is the name of the hook for which the status will be updated.

## Hook Structure
Webhooks to be run can be configured in `pju.webhook-field.hooks`.
The **key** in the hooks array determines the hook name. The array can take the following options:

Name | Type | Default | Description
--- | --- | --- | ---
url | `String` | - | The (outgoing) URL that will be called for the webhook.
method | `String` (http method) | `'POST'` | Optional: The http method for the outgoing webhook.
payload | `callable` | - | Optional: The payload that will be sent to the outgoing webhook. Needs to be a closure that should return an array.
callback | `callable` | - | Optional: A closure that will be run after the webhook was started or completed. It receices the `$status`and `$request` info. `$status` will be `'progress'`, `'error'` or `'success'`. `$request` is a `Kirby\Http\Request` object with the response from  the webhook.
showOutdated | `Boolean` | `true` | Enable warning message if the site content has been modified since hook was last triggered.

## Labels

All texts that the field displays can be customized ([see example configuration](#example-configphp)).

Status | Description (will not be displayed) |name | cta | text
--- | --- | --- | --- | ---
`new` | When a hook has never been triggered | New | Deploy now | The site has not been deployed yet.
`progress` | After the hook has triggered, before it has completed | Site is being deployed | Trigger new deploy | The site is being deployed.
`success` | The hook has successfully completed | Site is live | Deploy again | The site is live and up to date.
`error` | There was an error with running the hook |  Error | Trigger new deploy | There was an error while trying to deploy.
`outdated` | There were changes to the site content after the last time the hook was started | Deploy now | There where changes after the last deployment.
`hooksEmpty` | No hooks configured in config.php | Hooks are not defined | Deploy not available | You need to set the hooks in your Kirby configuration.
`hookNotfound` | The hook name the field is configured to use was not found | Deploy not available | The hook "`%hookName%`" was not found.
`hookNoUrl` | A hook was configured without URL | Deploy not available | The url for the hook "`%hookName%`" is not defined.

You can use the placeholder `%hookName%` in all labels to display the name of the hook.

## Example config.php

An example configuration could be:
```php
<?php
return [
    'pju.webhook-field.endpoint' => 'webhook-4kqkcf9jhu5jn4gx',
    'pju.webhook-field.hooks' => [
        'production' => [
            'url' => 'https://deploy-provider.dev/deploy/foo/production',
            'callback' => function($status) {
                if ($status === 'error') {
                    error_log('There was an error with the production webhook');
                }
            }
        ]
    ],
    'pju.webhook-field.labels' => [
        'success' => [
          'name' => 'Webhook %hookName% Successful',
          'cta'  => 'Run again'
        ]
    ]
```

# Field options
Webhooks fields can be set up in every blueprint for your site.
The following options are available for every webhook field:

Name | Type | Default | Description
--- | --- | --- | ---
label | `String` | `'Deploy Site'` | The label for the field.
hook | `String` | - | The name of the webhook that this field triggers (using the key name of a webhook configured in `pju.webhook-field.hooks`. If it is empty, the first hook will be used.
monochrome | `Boolean` | `false` | Turns icons black and white.
debug | `Boolean` | `true` | If debug logs should be echoed in the JS console.

## Example blueprint
An example blueprint for a webhooks field could be:

```yml
fields:
    deploy:
        type: webhook
        label: Deploy Production Site
        hook: production
        debug: false
```
