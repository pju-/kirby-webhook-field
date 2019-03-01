<?php

namespace pju;

use Kirby\Exception\InvalidArgumentException;

class Webhooks
{
  private static $allowed = ['success', 'progress', 'error'];

  private static function formatHookConfig(array $hooks, string $hookName): array
  {
    $config = $hooks[$hookName];

    if (is_string($config))
    {
      $config = ['url' => $config];
    }

    if (!isset($config['method']))
    {
      $config['method'] = 'POST';
    }

    return array_merge(['name' => $hookName], $config);
  }

  public static function getHook(string $hookName): array
  {
    $hooks = kirby()->option('pju.webhooks.hooks');

    if (!$hooks || count($hooks) === 0) {
      return ['name' => 'No hooks'];
    }

    if ($hookName === '') {
      reset($hooks);
      $firstKey = key($hooks);

      return Webhooks::formatHookConfig($hooks, $firstKey);
    }

    if (!isset($hooks[$hookName])) {
      return ['name' => 'Hook not found'];
    };

    return Webhooks::formatHookConfig($hooks, $hookName);
  }

  public static function setState(string $hookName, string $status)
  {
    if (!in_array($status, Webhooks::$allowed))
    {
      throw new InvalidArgumentException('Status not allowed');
    }

    $kirby = kirby();
    $kirby->impersonate('kirby');
    $cache = $kirby->cache('pju.webhooks');

    $state = $cache->get($hookName);

    $state['status'] = $status;

    // Don't save the time if we update to success - we want to know when the hook was triggered
    if ($status !== 'success')
    {
      $state['updated'] = time();
    }

    $cache->set($hookName, $state);

    return 'success';
  }

  public static function getState(string $hookName): array
  {
    $hooks = kirby()->option('pju.webhooks.hooks');

    if (!$hooks || count($hooks) === 0) return ['status' => 'hooksEmpty'];

    if (!isset($hooks[$hookName])) return ['status' => 'hookNotfound'];

    if (!isset($hooks[$hookName]['url']) || !$hooks[$hookName]['url']) return ['status' => 'hookNoUrl'];

    $kirby = kirby();
    $kirby->impersonate('kirby');
    $cache = $kirby->cache('pju.webhooks');
    $state = $cache->get($hookName);

    return $state ? $state : ['status' => 'new', 'updated' => 0];
  }
}
