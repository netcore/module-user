<?php

namespace Modules\User\Traits;

use Illuminate\Http\Request;
use Modules\Permission\Models\Role;
use Nwidart\Modules\Facades\Module;

trait UserPermissions
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|object
     */
    public function role()
    {
        $module = Module::find('Permission');

        if (! $module || !$module->enabled()) {
            return (object)[
                'levels' => collect()
            ];
        }

        return $this->belongsTo(Role::class);
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     *
     *
     * @param Request $request
     * @return bool
     * @internal param $name
     */
    public function hasPermission(Request $request): bool
    {
        $module = Module::find('Permission');
        $user = $this;

        if ($module && $module->enabled()) {
            $currentRoute = $request->route()->getName();
            if ($currentRoute == 'admin::permission.access-denied') {
                return true;
            }

            $role = $user->role;

            if (!$role && !$user->isAdmin()) {
                return false;
            }
            $levels = $role->levels;

            if (!$levels->count() && !$user->isAdmin()) {
                return false;
            }
            
            foreach ($levels as $level) {
                foreach ($level->routes as $route) {
                    if ($route->route) {
                        $selectedRoute = str_replace('*', '', $route->route);
                        $matches = preg_match('/' . $selectedRoute . '/', $currentRoute);

                        if ($route->route === '*' || $matches) {
                            return true;
                        }
                    }

                    if ($route->uri) {
                        $uri = (substr($route->uri, 0, 1) != '/' ? '/' . $route->uri : $route->uri);
                        if ($uri == $request->getRequestUri()) {
                            return true;
                        }
                    }
                }
            }
        } else {
            if ($user->isAdmin()) {
                return true;
            }
        }

        return false;
    }
}