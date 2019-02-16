# Kirby Webhooks

## What this does
This plugin provides a field to be used in [Kirby CMS](https://getkirby.com/). Use it to easily trigger [webhooks](https://en.wikipedia.org/wiki/Webhook) from the Kirby panel.

Probably the most common use for a webhook would be to trigger a build or deploy mechanism.

## Quick start guide

This plugin can be configured to do a lot of things. However, I tried to give it some sane defaults, so a basic use case can be set up as quickly as possible.

** If you just want to trigger a deploy hook, for example to on netlify, follow this guide: **

### 1 - Install

Copy plugin content to ```/site/plugins```.

### 2 - Set General Options (set in /site/config/config.php).

**Route**

The API route that you will use to update the status of hooks. Basically this provides a webhook for incoming requests.
You an also use this as a way to protect the API from unwanted access.

Example:
```
'pju.kirby-webhooks.route' => 'deploy-YOUR_API_TOKEN'
```

**Services**

An array of the webhooks that you want to provide.

Example:
```php
'pju.kirby-webhooks.hooks' => [
  [
    'name' => 'netlify',
    'url' => 'https://api.netlify.com/build_hooks/YOUR_BUILD_HOOK',
  ]
]
```

### 3 - Use Field (in Kirby blueprints)
 
Use the field as often as you want on the blueprints of any page you want to show it.

Example:
```yml
fields:
  deploy:
    type: deploy
    label: Deploy Site
```

If you want multiple hooks, for example `live` and `staging`, you can also reference the name of any hook you have set in your ```/site/config/config.php``` file.
If you leave the ```hook``` option empty instead, it will default to the first hook you have configured.

### 4 - Example configuration for netlify

Add two deploy notifications on netlify:

- Outgoing webhook: **Deploy succeeded** - URL: `https://www.yoursite.com/api/YOUR_ROUTE/success`
- Outgoing webhook: **Deploy failed** - URL: `https://www.yoursite.com/api/YOUR_ROUTE/error`

`YOUR_ROUTE` is the route from your `config.php`.
You do not need to set a JWS secret. Use a secret key in the route name instead.


## Options Overview

### General options - config.php

Name | Type | Default | Description
--- | --- | --- | ---
route | `String` | `"webhooks"` | The API endpoint for incoming webhooks. The endpoints for updating the status to success/error will be `https://www.yoursite.com/api/YOUR_ROUTE/success` and `https://www.yoursite.com/api/YOUR_ROUTE/error` (unless you have configured a [custom API location](https://getkirby.com/docs/guide/api/introduction#custom-api-location) in Kirby).
services | `Array` | `[]` | An array of services/hooks. Each service entry consists of a structured like this: `['name' => 'netlify', 'hook' => 'https://api.netlify.com/build_hooks/YOUR_BUILD_HOOK']`
lables | `Array` | [see below]() | An array of translations. Keys and default values can be found [here]().


### Webhook Structure (*pju.deploy.webhooks*)

Name | Type | Default | Description
--- | --- | --- | ---
name | `String` | - | The key that can be used in the blueprint field.
url | `String` | - | The (outgoing) URL that will be called for the webhook.
method | `String` (http method) | `post` | Optional: The http method for the outgoing webhook.
payload | `Array`, `callable` | [] | Optional: The payload that will be send to the outgoing webhook. Can be an array or a function/closure that returns an array.


### Field options - individual to each blueprint

Name | Type | Default | Description
--- | --- | --- | ---
label | `String` | 'Webhook' | The label for the field
service | `String` |  | The service that this field triggers (using the `name` of a service configured in your `config.php`. If it is empty, the first service will be used instead.
monochrome | `Boolean` | `false` | If the icons should be black and white (default is colour icons).


### Labels

#### Label placeholders

%hookName%, %contentChanged%, %hookChanged%, %differenceChanged%
