<?php
namespace Tilex\Annotation;

use Silex\Application;
use Tilex\Annotation\AnnotationClassProcessInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Annotations\CachedReader;

/**
 * AnnotationHandler
 */
class AnnotationHandler
{
    /** @var Application */
    protected $app = null;
    /** @var AnnotationReader */
    protected $reader = null;
    /** @var string[] */
    protected $dirs = [];

    /**
     * Constructor
     * @param Application $app
     * @param array $dirs
     * @param Doctrine\Common\Cache\Cache|String $cache
     */
    public function __construct(Application $app, array $dirs = [], $cache = null)
    {
        $this->app = $app;
        if ($cache === null) {
            $cache = new ArrayCache();
        } elseif (is_string($cache)) {
            $cache = new $cache();
        }
        if (!$cache instanceof Cache) {
            throw new \InvalidArgumentException('$cache has to be an instance of Doctrine\Common\Cache\Cache');
        }
        $this->reader = new CachedReader(new AnnotationReader(), $cache, $app['debug']);
        $this->dirs = $dirs;
    }

    /**
     * Handle the annotations and processes
     */
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

    /**
     * Lists PHP classes in $dirs and subdirectorys
     * @param array $dirs
     * @return multitype:string
     */
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

    /**
     * Find PHP classes in a File
     * @param string $path
     * @return multitype:string
     */
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