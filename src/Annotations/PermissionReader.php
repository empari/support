<?php namespace Empari\Support\Annotations;

use Empari\Support\Annotations\Mapping\Action;
use Empari\Support\Annotations\Mapping\Controller;
use Doctrine\Common\Annotations\Reader;

class PermissionReader
{
    /**
     * @var Reader
     */
    private $reader;
    /**
     * PermissionReader constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
    /**
     * Get all Controllers
     *
     * @return array
     */
    private function getControllers()
    {
        foreach (config('support::path.controllers') as $path) {
            $files = [];
            foreach (\File::allFiles($path) as $file) {
                require_once $file->getRealPath();
                $files [] = $file->getRealPath();
            }
        }
        return $files;
    }
    /**
     * Get All Permissions
     *
     * @return array
     */
    public function getPermissions()
    {
        $controllerClasses = $this->getControllers();
        $permissions = [];
        foreach (get_declared_classes() as $className) {
            $rc = new \ReflectionClass($className);
            if (in_array($rc->getFileName(), $controllerClasses)) {
                $permission = $this->getPermission($className);
                if (count($permission)) {
                    $permissions = array_merge($permissions, $permission);
                }
            }
        }
        return $permissions;
    }
    /**
     * Get Permissions by Controller Class
     *
     * @param $class
     * @return array
     */
    public function getPermission($class, $action = null)
    {
        $rc = new \ReflectionClass($class);
        /** @var Reader $controllerAnnotations */
        $controllerAnnotations = $this->reader->getClassAnnotation($rc, Controller::class);

        $permissions = [];
        if ($controllerAnnotations) {
            $permission = [
                'name' => $controllerAnnotations->name,
                'description' => $controllerAnnotations->description
            ];
            $rcMethods = !$action ? $rc->getMethods() : [$rc->getMethod($action)];
            foreach ($rcMethods as $rcMethod) {
                /** @var Action $annotationAction */
                $annotationAction = $this->reader->getMethodAnnotation($rcMethod, Action::class);
                if (!is_null($annotationAction)) {
                    $permission['resource_name'] = $annotationAction->name;
                    $permission['resource_description'] = $annotationAction->description;
                    $permissions [] = (new \ArrayIterator($permission))->getArrayCopy();
                }
            }
            $permissions [] = $permission;
        }
        return $permissions;
    }
}