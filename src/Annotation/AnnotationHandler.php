<?php
namespace Tilex\Annotation;

use Pimple\Container;
use Doctrine\Common\Annotations\AnnotationReader;

class AnnotationHandler
{
    /** @var Container */
    protected $app = null;
    /** @var AnnotationReader */
    protected $reader = null;

    public function __construct(Container $app, array $dirs = [])
    {
        $this->app = $app;
        $this->reader = new AnnotationReader();
    }

    public function listClasses(array $dirs)
    {
        $classes = [];
        foreach ($dirs as $dir) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($iterator as $path) {
            if ($path->isFile() && $path->getExtension() === 'php') {
                $classes = array_merge($classes, $this->findClassesInFile($path->getPathname()));
            }
          }
        }
        return $classes;
    }

    private function findClassesInFile($path)
    {
        $content = file_get_contents($path);
        $classes = [];

        $namespace = 0;
        $tokens = token_get_all($content);
        $count = count($tokens);
        $dlm = false;
        for ($i = 2; $i < $count; $i++) {
          if ((isset($tokens[$i - 2][1]) && ($tokens[$i - 2][1] == "phpnamespace" || $tokens[$i - 2][1] == "namespace")) ||
            ($dlm && $tokens[$i - 1][0] == \T_NS_SEPARATOR && $tokens[$i][0] == \T_STRING)) {
              if (!$dlm) $namespace = 0;
              if (isset($tokens[$i][1])) {
                $namespace = $namespace ? $namespace . "\\" . $tokens[$i][1] : $tokens[$i][1];
                $dlm = true;
              }
            }
            elseif ($dlm && ($tokens[$i][0] != \T_NS_SEPARATOR) && ($tokens[$i][0] != \T_STRING)) {
              $dlm = false;
            }
            if (($tokens[$i - 2][0] == \T_CLASS || (isset($tokens[$i - 2][1]) && $tokens[$i - 2][1] == "phpclass"))
              && $tokens[$i - 1][0] == \T_WHITESPACE && $tokens[$i][0] == \T_STRING) {
                $class_name = $tokens[$i][1];
                $classes[] = $namespace.'\\'.$class_name;
              }
        }
        return $classes;
    }
}