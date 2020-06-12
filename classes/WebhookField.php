<?php

namespace pju\KirbyWebhookField;

use Kirby\Exception\InvalidArgumentException;

/**
 * Class WebhookField
 * @package pju\KirbyWebhookField
 */
class WebhookField
{
    /**
     * The allowed status codes. Lists only persisting status codes
     * Temporary ones such as hookNotFound can not be manually set
     *
     * @var array
     */
    private static $allowedStates = ['success', 'progress', 'error'];

    /**
     * Format the configuration for a certain hook
     *
     * @param string $hookName
     * @return array
     */
    private static function formatHookConfig(string $hookName): array
    {
        $hooks = kirby()->option('pju.webhook-field.hooks');
        $config = $hooks[$hookName];

        if (is_string($config)) {
            $config = ['url' => $config];
        }

        if (!isset($config['method'])) {
            $config['method'] = 'POST';
        }

        if (isset($config['payload']) && is_callable($config['payload'])) {
            $payload = $config['payload'];
            $config['payload'] = $payload();
        }

        $config['showOutdated'] = $config['showOutdated'] ?? true;

        return array_merge(['name' => $hookName], $config);
    }

    /**
     * Get the hook config for a certain hook
     * Falls back to the first hook in the config if it is not explicitly set
     *
     * @param string $hookName
     * @return array
     */
    public static function getHook(string $hookName): array
    {
        $hooks = kirby()->option('pju.webhook-field.hooks');

        if (!$hooks || count($hooks) === 0) {
            return ['name' => 'No hooks'];
        }

        if ($hookName === '') {
            reset($hooks);
            $firstKey = key($hooks);

            return WebhookField::formatHookConfig($firstKey);
        }

        if (!isset($hooks[$hookName])) {
            // TODO: find a better way?
            return ['name' => 'Hook not found'];
        };

        return WebhookField::formatHookConfig($hookName);
    }

    /**
     * Set the status of a hook to the specified state
     * Returns a message for debugging
     *
     * @param string $hookName
     * @param string $status
     * @return string
     * @throws InvalidArgumentException
     */
    public static function setState(string $hookName, string $status)
    {
        if (!in_array($status, WebhookField::$allowedStates)) {
            throw new InvalidArgumentException('Status not allowed');
        }

        $kirby = kirby();
        $kirby->impersonate('kirby');
        $cache = $kirby->cache('pju.webhook-field');

        $state = $cache->get($hookName);
        $state['status'] = $status;

        // Don't save the time if we update to success - we want to know when the hook was triggered
        if ($status !== 'success') {
            $state['updated'] = time();
        }

        $cache->set($hookName, $state);

        return 'status for ' . $hookName . ' changed to ' . $status;
    }

    /**
     * Runs a callback for the specified hook
     * The callback receives the status and the
     *
     * @param string $hookName
     * @param string $status
     */
    public static function runCallback(string $hookName, string $status)
    {
        $hook = WebhookField::getHook($hookName);

        if (isset($hook['callback']) && is_callable($hook['callback'])) {
            $req = kirby()->request();
            $hook['callback']($status, $req);
        }
    }

    /**
     * Get the state of the specified hook
     *
     * @param string $hookName
     * @return array
     */
    public static function getState(string $hookName): array
    {
        $hooks = kirby()->option('pju.webhook-field.hooks');

        if (!$hooks || count($hooks) === 0) {
            return ['status' => 'hooksEmpty'];
        }

        if (!isset($hooks[$hookName])) {
            return ['status' => 'hookNotfound'];
        }

        if (!isset($hooks[$hookName]['url']) || !$hooks[$hookName]['url']) {
            return ['status' => 'hookNoUrl'];
        }

        $kirby = kirby();
        $kirby->impersonate('kirby');
        $cache = $kirby->cache('pju.webhook-field');
        $state = $cache->get($hookName);

        return $state ? $state : ['status' => 'new', 'updated' => null];
    }
}
