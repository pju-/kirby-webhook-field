<?php

namespace pju;

use Kirby\Exception\InvalidArgumentException;

class Webhooks
{
  private static $allowed = ['success', 'progress', 'error'];

  public static function getHook(String $name): array
  {
    $hooks = kirby()->option('pju.webhooks.hooks');

    if (!$hooks || count($hooks) === 0) {
      return ['name' => 'No hooks'];
    }

    if ($name === '') {
      reset($hooks);
      $firstKey = key($hooks);

      return array_merge(['name' => $firstKey], $hooks[$firstKey]);
    }

    if (!isset($hooks[$name])) {
      return ['name' => 'Hook not found'];
    };

    return array_merge(['name' => $name], $hooks[$name]);
  }

  public static function setStatus(String $hook, String $status)
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

  public static function getStatus(String $hookName): array
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
