<?php
namespace Tilex\Annotation;

use Silex\Application;
use Doctrine\Common\Annotations\AnnotationReader;
use Tilex\Annotation\AnnotationClassProcessInterface;

class AnnotationHandler
{
    /** @var Application */
    protected $app = null;
    /** @var AnnotationReader */
    protected $reader = null;
    /** @var string[] */
    protected $dirs = [];

    public function __construct(Application $app, array $dirs = [])
    {
        $this->app = $app;
        $this->reader = new AnnotationReader();
        $this->dirs = $dirs;
    }

    function handle()
    {
        $classes = $this->listClasses($this->dirs);
        foreach ($classes as $classname) {
            $rc = new \ReflectionClass($classname);
            $class_annotations = $this->reader->getClassAnnotations($rc);
            foreach ($class_annotations as $c_annotation) {
                if($c_annotation instanceof AnnotationClassProcessInterface) {
                    $c_annotation->process($this->app, $rc);
                }
            }
            $methods = $rc->getMethods();
            foreach ($methods as $method) {
                $method_annotations = $this->reader->getMethodAnnotations($method);
                foreach ($method_annotations as $m_annotation) {
                    if ($m_annotation instanceof AnnotationMethodProcessInterface) {
                        $m_annotation->process($this->app, $method, $class_annotations);
                    }
                }
            }
        }
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
          if ((isset($tokens[$i - 2][1]) && ($tokens[$i - 2][1] == 'phpnamespace' || $tokens[$i - 2][1] == 'namespace')) ||
            ($dlm && $tokens[$i - 1][0] == \T_NS_SEPARATOR && $tokens[$i][0] == \T_STRING)) {
              if (!$dlm) $namespace = 0;
              if (isset($tokens[$i][1])) {
                $namespace = $namespace ? $namespace . '\\' . $tokens[$i][1] : $tokens[$i][1];
                $dlm = true;
              }
            }
            elseif ($dlm && ($tokens[$i][0] != \T_NS_SEPARATOR) && ($tokens[$i][0] != \T_STRING)) {
              $dlm = false;
            }
            if (($tokens[$i - 2][0] == \T_CLASS || (isset($tokens[$i - 2][1]) && $tokens[$i - 2][1] == 'phpclass'))
              && $tokens[$i - 1][0] == \T_WHITESPACE && $tokens[$i][0] == \T_STRING) {
                $class_name = $tokens[$i][1];
                $classes[] = $namespace.'\\'.$class_name;
              }
        }
        return $classes;
    }
}