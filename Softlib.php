<?php

namespace addons\softlib;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Softlib extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [];
        $menu_file = ADDON_PATH . 'softlib' . DS . 'data' . DS . 'menu.php';
        if (is_file($menu_file)) {
            $menu = include $menu_file;
        }
        if ($menu) {
            Menu::create($menu);
        }
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('softlib');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('softlib');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('softlib');
        return true;
    }

    /**
     * 插件升级方法
     * @return bool
     */
    public function upgrade()
    {
        //如果菜单有变更则升级菜单
        $menu = include ADDON_PATH . 'softlib' . DS . 'data' . DS . 'menu.php';

        Menu::upgrade('softlib', $menu);
        return true;
    }

}
