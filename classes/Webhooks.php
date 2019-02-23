<?php

namespace pju;

use Kirby\Exception\InvalidArgumentException;

class Webhooks
{
  private static $allowed = ['success', 'progress', 'error'];

  private static function formatHookConfig(array $hooks, string $name): array
  {
    $config = $hooks[$name];

    if (is_string($config))
    {
      $config = ['url' => $config];
    }

    if (!isset($config['method']))
    {
      $config['method'] = 'POST';
    }

    return array_merge(['name' => $name], $config);
  }

  public static function getHook(string $name): array
  {
    $hooks = kirby()->option('pju.webhooks.hooks');

    if (!$hooks || count($hooks) === 0) {
      return ['name' => 'No hooks'];
    }

    if ($name === '') {
      reset($hooks);
      $firstKey = key($hooks);

      return Webhooks::formatHookConfig($hooks, $firstKey);
    }

    if (!isset($hooks[$name])) {
      return ['name' => 'Hook not found'];
    };

    return Webhooks::formatHookConfig($hooks, $name);
  }

  public static function setStatus(string $hook, string $status)
  {
    if (!in_array($status, Webhooks::$allowed))
    {
      throw new InvalidArgumentException('Status not allowed');
    }

    // TODO: Handling new status from route

    $kirby = kirby();
    $kirby->impersonate('kirby');
    $cache = $kirby->cache('pju.webhooks');

    $cache->set($hook, [
      'status' => $status,
      'hookUpdated' => time()
    ]);

    return 'success';
  }

  public static function getStatus(string $hookName): array
  {
    $hooks = kirby()->option('pju.webhooks.hooks');

    if (!$hooks || count($hooks) === 0) return ['status' => 'hooksEmpty'];

    if (!isset($hooks[$hookName])) return ['status' => 'hookNotfound'];

    if (!isset($hooks[$hookName]['url']) || !$hooks[$hookName]['url']) return ['status' => 'hookNoUrl'];

    $kirby = kirby();
    $kirby->impersonate('kirby');
    $cache = $kirby->cache('pju.webhooks');
    $cachedStatus = $cache->get($hookName);

    return $cachedStatus ? $cachedStatus : ['status' => 'new'];
  }
}
