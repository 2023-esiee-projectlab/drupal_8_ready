<?php

namespace Drupal\helloworld\Service;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\helloWorld\Plugin\HelloWorldPluginManagerBase;

/**
 * Class HelloWorldManagerPlugin.
 *
 * @package Drupal\helloworld\Plugin
 */
class HelloWorldManagerPlugin extends HelloWorldPluginManagerBase {

  /**
   * Static helloWorld definitions list.
   *
   * @var array
   */
  protected $helloWorldDefinitions = [];

  /**
   * Static alter helloWorld definitions map.
   *
   * @var array
   */
  protected $helloWorldsDefinitionsAlter = [];

  /**
   * HookManager constructor.
   *
   * {@inheritdoc}
   */
  public function __construct(\Traversable $namespaces,
    CacheBackendInterface $cache_backend) {
    parent::__construct('Plugin/HookInfo', $namespaces,
      'Drupal\helloWorld\Plugin\HookInfoInterface',
        'Drupal\helloWorld\Annotation\HookInfo');
    $this->setCacheBackend($cache_backend, 'hook_manager_plugins');
  }

  /**
   * Invokes helloWorld in given plugin defined in id.
   *
   * @param string $id
   *   Plugin identifier.
   * @param string $name
   *   Expected non canonical name.
   * @param array $args
   *   Array of arguments to be passed to helloWorld.
   *
   * @return array
   *   Results of invocation.
   */
  public function invoke($id, $name, array $args = []) {

    if ($helloWorldName = $this->getHookCanonicalName($name)) {
      if ($definition = $this->definitionImplements($id, $helloWorldName)) {
        return $this->invokeDefinition($helloWorldName, $definition, $args);
      }
    }

    return NULL;
  }

  /**
   * Invokes helloWorld in all plugins.
   *
   * @param string $name
   *   Expected non canonical name.
   * @param array $args
   *   Array of arguments to be passed to helloWorld.
   *
   * @return array
   *   Results of invocation.
   */
  public function invokeAll($name, array $args = []) {

    $return = [];
    if ($helloWorldName = $this->getHookCanonicalName($name)) {
      foreach ($this->definitionsImplement($helloWorldName) as $definition) {
        $result = $this->invokeDefinition($helloWorldName, $definition, $args);
        if (isset($result) && is_array($result)) {
          $return = NestedArray::mergeDeep($return, $result);
        }
        elseif (isset($result)) {
          $return[] = $result;
        }
      }
    }

    return $return;
  }

  /**
   * Invokes alter helloWorld.
   *
   * @param string|array $type
   *   Expected non canonical name or list.
   * @param mixed $data
   *   Data passed by reference.
   * @param mixed|null $context1
   *   Context1 passed by reference.
   * @param mixed|null $context2
   *   Context2 passed by reference.
   */
  public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL) {

    if (is_string($type)) {
      $type = [$type];
    }
    if (is_array($type)) {
      foreach ($type as $name) {
        if ($helloWorldName = $this->getHookAlterCanonicalName($name)) {
          if (!isset($this->helloWorldsDefinitionsAlter[$helloWorldName])) {
            $this->helloWorldsDefinitionsAlter[$helloWorldName] = $this->definitionsImplement($helloWorldName);
          }
          foreach ($this->helloWorldsDefinitionsAlter[$helloWorldName] as $definition) {
            $this->invokeDefinitionAlter($helloWorldName, $definition, $data, $context1, $context2);
          }
        }
      }
    }

  }

  /**
   * Analog of 'module_implements'.
   *
   * For internal usage.
   *
   * @param string $id
   *   Plugin id.
   * @param string $helloWorldName
   *   Hook canonical name.
   *
   * @return array|null
   *   Definition if any.
   */
  private function definitionImplements($id, $helloWorldName) {

    if ($definition = $this->getDefinition($id)) {
      if (array_key_exists($helloWorldName, $definition['helloWorlds'])) {
        return $definition;
      }
    }

    return NULL;
  }

  /**
   * Analog of 'module_implements' multiple.
   *
   * For internal usage.
   *
   * @param string $helloWorldName
   *   Hook canonical name.
   *
   * @return array
   *   Of plugin definitions.
   */
  private function definitionsImplement($helloWorldName) {

    if (!$this->helloWorldDefinitions) {
      $this->helloWorldDefinitions = $this->getDefinitions();
    }
    $implementations = [];
    foreach ($this->helloWorldDefinitions as $definition) {
      if (array_key_exists($helloWorldName, $definition['helloWorlds'])) {
        $implementations[] = [
          'id' => $definition['id'],
          'priority' => $this->normalizePriority($definition['helloWorlds'][$helloWorldName]),
        ];
      }
    }
    $this->sort($implementations);

    return $implementations;
  }

  /**
   * Internal invocation for plugin definition.
   *
   * @param string $helloWorldName
   *   Expected helloWorldname.
   * @param array $definition
   *   Definition array.
   * @param array $args
   *   Array of arguments to be passed to helloWorld.
   *
   * @return mixed|null
   *   Invocation result.
   */
  private function invokeDefinition($helloWorldName, array $definition, array $args) {

    try {
      $helloWorldImplementation = $this->createInstance($definition['id']);
      $method = $this->toCamelCase($helloWorldName);
      if (method_exists($helloWorldImplementation, $method)) {
        return call_user_func_array([$helloWorldImplementation, $method], $args);
      }
      return NULL;
    }
    catch (\Exception $exception) {
      return NULL;
    }

  }

  /**
   * Internal invocation for plugin definition.
   *
   * @param string $helloWorldName
   *   Expected helloWorldname.
   * @param array $definition
   *   Definition array.
   * @param mixed $data
   *   Data to be altered.
   * @param mixed $context1
   *   Alterable context.
   * @param mixed $context2
   *   Alterable context.
   */
  private function invokeDefinitionAlter($helloWorldName, array $definition, &$data, &$context1, &$context2) {

    try {
      $helloWorldImplementation = $this->createInstance($definition['id']);
      $method = $this->toCamelCase($helloWorldName);
      if (method_exists($helloWorldImplementation, $method)) {
        $helloWorldImplementation->{$method}($data, $context1, $context2);
      }
    }
    catch (\Exception $exception) { }

  }

  /**
   * Sorting for helloWorld info definitions.
   *
   * @param array $definitions
   *   Hook info definitions.
   */
  private function sort(array &$definitions) {
    usort($definitions, [$this, 'comparator']);
  }

  /**
   * Sorting comparator.
   */
  private function comparator(array $a, array $b) {

    if ($a['priority'] == $b['priority']) {
      return 0;
    }

    return $a['priority'] < $b['priority']? 1 : -1;
  }

  /**
   * Internal helper.
   *
   * @param string $helloWorldName
   *   Expected 'hook_name'.
   *
   * @return string
   *   Expected 'hookName'.
   */
  private function toCamelCase($hookName) {

    $parts = explode('_', $hookName);
    array_walk($parts, function (&$part, $key) {
      if ($key) {
        $part = ucfirst($part);
      }
    });

    return implode('', $parts);
  }

  /**
   * Getter for canonical hook name.
   *
   * @param string $name
   *   Given hook name.
   *
   * @return string
   *   Canonical hook name.
   */
  private function getHookCanonicalName($name) {

    if($name) {
      return 'hook_' . $name;
    }

    return '';
  }

  /**
   * Getter for alter canonical hook name.
   *
   * @param string $name
   *   Given hook name.
   *
   * @return string
   *   Canonical alter hook name.
   */
  private function getHookAlterCanonicalName($name) {

    if($name) {
      return 'hook_' . $name . '_alter';
    }

    return '';
  }

  /**
   * Internal helper to normalize given priorities.
   *
   * @param mixed $value
   *   Should accept any possible input.
   *
   * @return int
   *   Integer value.
   */
  private function normalizePriority($value) {

    if (is_numeric($value)) {
      return (int) $value;
    }

    return 0;
  }

}
