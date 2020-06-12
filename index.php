<?php
load([
    'pju\\KirbyWebhookField\\WebhookField' => 'classes/WebhookField.php'
], __DIR__);

use \pju\KirbyWebhookField\WebhookField;

Kirby::plugin('pju/webhook-field', [
    'options' => [
        'endpoint' => 'webhook',
        'hooks' => [],
        'labels' => [
            'new' => [
                'name' => 'New',
                'cta' => 'Deploy now',
                'text' => 'The site has not been deployed yet.',
            ],
            'progress' => [
                'name' => 'Site is being deployed',
                'cta' => 'Trigger new deploy',
                'text' => 'The site is being deployed.',
            ],
            'success' => [
                'name' => 'Site is live',
                'cta' => 'Deploy again',
                'text' => 'The site is live and up to date.',
            ],
            'error' => [
                'name' => 'Error',
                'cta' => 'Trigger new deploy',
                'text' => 'There was an error while trying to deploy.',
            ],
            'outdated' => [
                'name' => 'Undeployed changes',
                'cta' => 'Deploy now',
                'text' => 'There where changes after the last deployment.',
            ],
            'hooksEmpty' => [
                'name' => 'Hooks are not defined',
                'cta' => 'Deploy not available',
                'text' => 'You need to set the hooks in your Kirby configuration.',
            ],
            'hookNotfound' => [
                'name' => 'Service not found',
                'cta' => 'Deploy not available',
                'text' => 'The hook "%hookName%" was not found.',
            ],
            'hookNoUrl' => [
                'name' => 'No URL set',
                'cta' => 'Deploy not available',
                'text' => 'The url for the hook "%hookName%" is not defined.',
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
                'name' => function (string $name = 'webhook') {
                    return $name;
                },
                'hook' => function (string $name = '') {
                    return WebhookField::getHook($name);
                },
                'debug' => function (bool $debugEnabled = true) {
                    return $debugEnabled;
                },
                'monochrome' => function (bool $isMonochrome = false) {
                    return $isMonochrome;
                }
            ],
            'computed' => [
                'endpoint' => function () {
                    return kirby()->option('pju.webhook-field.endpoint');
                },
                'statusInitial' => function () {
                    $state = WebhookField::getState($this->hook['name']);

                    return $state['status'];
                },
                'hookUpdated' => function () {
                    $state = WebhookField::getState($this->hook['name']);

                    return isset($state['updated']) ? $state['updated'] : 0;
                },
                'siteModified' => function () {
                    return kirby()->site()->modified();
                },
                'labels' => function () {
                    $labels = kirby()->option('pju.webhook-field.labels');

                    return array_map(function ($label) {
                        return array_map(function ($text) {
                            $name = $this->name;

                            return str_replace('%hookName%', $name, $text);
                        }, $label);
                    }, $labels);
                }
            ]
        ]
    ],
    'routes' => function ($kirby) {
        $endpoint = $kirby->option('pju.webhook-field.endpoint');

        if (!$endpoint) {
            throw new InvalidArgumentException('Webhook plugin endpoint is not defined');
        }

        return [
            [
                'pattern' => $endpoint . '/(:any)/status',
                'action' => function ($hook) {
                    return WebhookField::getState($hook);
                },
                'method' => 'GET'
            ],
            [
                'pattern' => $endpoint . '/(:any)/(:any)',
                'action' => function ($hook, $status) {
                    $message = WebhookField::setState($hook, $status);

                    try {
                        WebhookField::runCallback($hook, $status);
                    } catch (Throwable $e) {
                        $message = 'error running callback for ' . $hook . ': ' . $e->getMessage();
                    }

                    return $message;
                },
                'method' => 'POST'
            ],
            [
                'pattern' => $endpoint . '/site-modified',
                'action' => function () {
                    return [
                        'modified' => kirby()->site()->modified()
                    ];
                },
                'method' => 'GET'
            ]
        ];
    }
]);
