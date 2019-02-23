<?php
use \pju\Webhooks;

Kirby::plugin('pju/webhooks', [
  'options' => [
    'route' => 'webhooks',
    'hooks' => [],
    'labels' => [
      'new' => [
        'name' => 'New',
        'cta'  => 'Deploy now',
        'text'  => 'The site has not been deployed yet.',
      ],
      'progress' => [
        'name' => 'Site is being deployed',
        'cta'  => 'Trigger new deploy',
        'text'  => 'The site is being deployed.',
      ],
      'success' => [
        'name' => 'Site is live',
        'cta'  => 'Deploy again',
        'text'  => 'The site is live and up to date.',
      ],
      'error' => [
        'name' => 'Error',
        'cta'  => 'Trigger new deploy',
        'text'  => 'There was an error while trying to deploy.',
      ],
      'outdated' => [
        'name' => 'Undeployed changes',
        'cta'  => 'Deploy now',
        'text'  => 'There where changes after the last deployment.',
      ],
      'hooksEmpty' => [
        'name' => 'Hooks are not defined',
        'cta'  => 'Deploy not available',
        'text'  => 'You need to set the hooks in your Kirby configuration.',
      ],
      'hookNotfound' => [
        'name' => 'Service not found',
        'cta'  => 'Deploy not available',
        'text'  => 'The hook "%hookName%" was not found.',
      ],
      'hookNoUrl' => [
        'name' => 'No URL set',
        'cta'  => 'Deploy not available',
        'text'  => 'The url for the hook "%hookName%" is not defined.',
      ],
    ],
    'cache' => true
  ],
  'fields' => [
    'webhook' => [
      'props' => [
        'label' => function (string $title = 'Deploy Status') {
          return $title;
        },
        'hook' => function (string $name = '') {
          return Webhooks::getHook($name);
        },
        'monochrome' => function (bool $isMonochrome = false) {
          return $isMonochrome;
        }
      ],
      'computed' => [
        'initialStatus' => function() {
          return Webhooks::getStatus($this->hook['name']);
        },
        'hooksChanged' => function() {
          $cache = kirby()->cache('pju.webhooks');
          return $cache->get('hooksChanged');
        },
        'contentChanged' => function() {
          return kirby()->site()->modified();
        },
        'labels' => function() {
          return kirby()->option('pju.webhooks.labels');
        }
      ]
    ]
  ],
  'routes' => function($kirby) {
    $route = $kirby->option('pju.webhooks.route');

    if (!$route)
    {
      throw new InvalidArgumentException('Webhook plugin route is not defined');
    }

    return [
      [
        'pattern' => $route . '/(:any)/status',
        'action'  => function($hook) {
          return Webhooks::getStatus($hook);
        },
        'method' => 'GET'
      ],
      [
        'pattern' => $route . '/(:any)/(:any)',
        'action'  => function($hook, $status) {
          Webhooks::setStatus($hook, $status);
          return [$status];
        },
        'method' => 'POST|UPDATE'
      ],
    ];
  }
]);

