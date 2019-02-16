# Kirby Webhooks

## What this does
This plugin provides a field to be used in [Kirby CMS](https://getkirby.com/). Use it to easily trigger [webhooks](https://en.wikipedia.org/wiki/Webhook) from the Kirby panel.

Probably the most common use for a webhook would be to trigger a build or deploy mechanism.

## Quick start guide

### 1 - Install

Copy plugin content to ```/site/plugins```.

### 2 - Set General Options (set in /site/config/config.php).

**Route**

The API route that you will use to update the status of hooks.
Use this as a way to protect the API from unwanted access.

Example:
```
'pju.deploy.route' => 'deploy-YOUR_API_TOKEN'
```

**Services**

An array of the hooks that you want to provide.

Example:
```php
'pju.deploy.services' => [
  [
    'name' => 'netlify',
    'hook' => 'https://api.netlify.com/build_hooks/YOUR_BUILD_HOOK',
  ],
  [
    'name' => 'netlify-staging',
    'hook' => 'https://api.netlify.com/build_hooks/YOUR_BUILD_HOOK_FOR_STAGING',
  ]
]
```

### 3 - Use Field (in Kirby blueprints)
 
Use the field as often as you want on the blueprints of any page you want to show it.

Example:
```yml
deploy_staging:
  type: deploy
  label: Deploy Staging
  serviceName: netlify-staging
```

You can reference the name of any service you have set in your ```/site/config/config.php``` file.
If you leave the ```serviceName``` option empty, it will default to the first service you have configured.

### 4 - Example configuration for netlify

// TODO: Describe Netlify setup


## Options Overview

### General options - config.php

Name | Type | Default | Description
--- | --- | --- | ---
route | `String` | `"webhooks"` | The API endpoint. The endpoints for updating the status to success/error will be `https://www.yoursite.com/api/YOUR_ROUTE/success` and `https://www.yoursite.com/api/YOUR_ROUTE/error` (unless you have configured a [custom API location](https://getkirby.com/docs/guide/api/introduction#custom-api-location) in Kirby).
services | `Array` | `[]` | An array of services/hooks. Each service entry consists of a structured like this: `['name' => 'netlify', 'hook' => 'https://api.netlify.com/build_hooks/YOUR_BUILD_HOOK']`
lables | `Array` | [see below]() | An array of translations. Keys and default values can be found [here]().


### Field options - individual to each blueprint

Name | Type | Default | Description
--- | --- | --- | ---
label | `String` | 'Webhook' | The label for the field
service | `String` |  | The service that this field triggers (using the `name` of a service configured in your `config.php`. If it is empty, the first service will be used instead.
monochrome | `Boolean` | `false` | If the icons should be black and white (default is colour icons).


### Labels

#### Label placeholders

%hookName%, %contentChanged%, %hookChanged%, %differenceChanged%
