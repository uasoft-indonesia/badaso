<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;
use Uasoft\Badaso\Models\Menu;
use Uasoft\Badaso\Models\MenuItem;
use Uasoft\Badaso\Models\Permission;

class BadasoMenuController extends Controller
{
    public function browseMenu(Request $request)
    {
        try {
            $menus = Menu::all();

            $data['menus'] = $menus;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function browseMenuItem(Request $request)
    {
        try {
            $request->validate([
                'menu_id' => ['required'],
            ]);

            $menu_items = MenuItem::where('menu_id', $request->menu_id)
                    ->orderBy('order', 'asc')
                    ->whereNull('menu_items.parent_id')
                    ->get();

            $menu_items = $this->getChildMenuItems($menu_items);

            $data['menu_items'] = $menu_items;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function readMenu(Request $request)
    {
        try {
            $request->validate([
                'menu_id' => ['required', 'exists:menus,id'],
            ]);
            $menu = Menu::find($request->menu_id);

            $data['menu'] = $menu;

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function readMenuItem(Request $request)
    {
        try {
            $request->validate([
                'menu_id'      => ['required', 'exists:menus,id'],
                'menu_item_id' => ['required', 'exists:menu_items,id'],
            ]);

            $menu_item = MenuItem::where('menu_id', $request->menu_id)->where('id', $request->menu_item_id)->first();

            $data['menu_item'] = $menu_item;

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function browseMenuItemByKey(Request $request)
    {
        try {
            $request->validate([
                'menu_key' => ['required', 'exists:menus,key'],
            ]);
            $menu = Menu::where('key', $request->menu_key)->first();

            $all_menu_items = MenuItem::join('menus', 'menus.id', 'menu_items.menu_id')
                    ->where('menus.key', $request->menu_key)
                    ->whereNull('menu_items.parent_id')
                    ->select('menu_items.*')
                    ->orderBy('menu_items.order', 'asc')
                    ->get();
            $menu_items = [];
            foreach ($all_menu_items as $menu_item) {
                $allowed = AuthenticatedUser::isAllowedTo($menu_item->permissions);
                if ($allowed) {
                    $menu_items[] = $menu_item;
                }
            }
            $menu_items = $this->getChildMenuItems($menu_items);

            $data['menu'] = $menu;
            $data['menu_items'] = $menu_items;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function browseMenuItemByKeys(Request $request)
    {
        try {
            $request->validate([
                'menu_key' => ['required'],
            ]);

            $menu_keys = explode(',', $request->menu_key);
            
            foreach ($menu_keys as $key => $menu_key) {
                $menu = Menu::where('key', $menu_key)->first();

                $all_menu_items = MenuItem::join('menus', 'menus.id', 'menu_items.menu_id')
                        ->where('menus.key', $menu_key)
                        ->whereNull('menu_items.parent_id')
                        ->select('menu_items.*')
                        ->orderBy('menu_items.order', 'asc')
                        ->get();
                $menu_items = [];
                foreach ($all_menu_items as $menu_item) {
                    $allowed = AuthenticatedUser::isAllowedTo($menu_item->permissions);
                    if ($allowed) {
                        $menu_items[] = $menu_item;
                    }
                }
                $menu_items = $this->getChildMenuItems($menu_items);

                $data[] = ['menu' => $menu, 'menu_items' => $menu_items];
            }

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    private function getChildMenuItems($menu_items)
    {
        $new_menu_items = $menu_items;
        foreach ($new_menu_items as $key => $value) {
            if ($value->hasChildren()) {
                $all_childrens = MenuItem::where('parent_id', $value->id)
                    ->orderBy('order', 'asc')
                    ->get();
                $childrens = [];
                foreach ($all_childrens as $children) {
                    $allowed = AuthenticatedUser::isAllowedTo($children->permissions);
                    if ($allowed) {
                        $childrens[] = $children;
                    }
                }
                $children = $this->getChildMenuItems($childrens);
                $value['children'] = collect($children)->toArray();
            }
        }

        return $new_menu_items;
    }

    public function addMenu(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'key'          => ['required', 'unique:menus'],
                'display_name' => ['required'],
            ]);

            $new_menu = new Menu();
            $new_menu->key = $request->get('key');
            $new_menu->display_name = $request->get('display_name');
            $new_menu->icon = $request->get('icon');
            $new_menu->save();

            DB::commit();

            return ApiResponse::success($new_menu);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function addMenuItem(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'menu_id' => ['required', 'exists:menus,id'],
                'title'   => ['required'],
                'url'     => ['required'],
                'target'  => ['required'],
            ]);

            $url = $request->get('url');
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                $url = substr($request->get('url'), 0, 1) != '/' ? '/'.$request->get('url') : $request->get('url');
            }

            $new_menu_item = new MenuItem();
            $new_menu_item->menu_id = $request->get('menu_id');
            $new_menu_item->title = $request->get('title');
            $new_menu_item->url = $url;
            $new_menu_item->target = $request->get('target') ? $request->get('target') : '_self';
            $new_menu_item->icon_class = $request->get('icon_class');
            $new_menu_item->color = $request->get('color');
            $new_menu_item->parent_id = $request->get('parent_id');
            $new_menu_item->order = $new_menu_item->highestOrderMenuItem();
            $new_menu_item->save();

            DB::commit();

            return ApiResponse::success($new_menu_item);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function editMenu(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'menu_id'      => ['required', 'exists:menus,id'],
                'key'          => ['required', "unique:menus,id,{$request->menu_id}"],
                'display_name' => ['required'],
            ]);

            $menu = Menu::find($request->menu_id);
            $menu->key = $request->get('key');
            $menu->display_name = $request->get('display_name');
            $menu->icon = $request->get('icon');
            $menu->save();

            DB::commit();

            return ApiResponse::success($menu);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function editMenuItem(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'menu_id'      => ['required', 'exists:menus,id'],
                'menu_item_id' => ['required', 'exists:menu_items,id'],
                'title'        => ['required'],
                'url'          => ['required'],
                'target'       => ['required'],
            ]);

            $url = $request->get('url');
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                $url = substr($request->get('url'), 0, 1) != '/' ? '/'.$request->get('url') : $request->get('url');
            }

            $menu_item = MenuItem::find($request->menu_item_id);
            $menu_item->menu_id = $request->get('menu_id');
            $menu_item->title = $request->get('title');
            $menu_item->url = $url;
            $menu_item->target = $request->get('target') ? $request->get('target') : '_self';
            $menu_item->icon_class = $request->get('icon_class');
            $menu_item->color = $request->get('color');
            $menu_item->save();

            DB::commit();

            return ApiResponse::success($menu_item);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function editMenuItemOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'menu_id'      => ['required', 'exists:menus,id'],
                'menu_item_id' => ['required', 'exists:menu_items,id'],
            ]);
            $menu_item = MenuItem::find($request->menu_item_id);
            $order = $request->get('order');

            $old_order = $menu_item->order;
            $new_order = $order;

            if (is_null($old_order)) {
                $old_order = 0;
            }

            if ($new_order > $old_order) {
                $menu_items = MenuItem::where('order', '<=', $new_order)
                    ->where('order', '>', $old_order)
                    ->where('menu_id', $request->menu_id)
                    ->get();
                foreach ($menu_items as $item) {
                    $other_menu_item = MenuItem::find($item->id);
                    $other_menu_item->order = $other_menu_item->order - 1;
                    $other_menu_item->save();
                }
            } else {
                $menu_items = MenuItem::where('order', '>=', $new_order)
                    ->where('order', '<', $old_order)
                    ->where('menu_id', $request->menu_id)
                    ->get();
                foreach ($menu_items as $item) {
                    $other_menu_item = MenuItem::find($item->id);
                    $other_menu_item->order = $other_menu_item->order + 1;
                    $other_menu_item->save();
                }
            }

            $menu_item->order = $order;
            $menu_item->save();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function editMenuItemsOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'menu_id'    => ['required', 'exists:menus,id'],
                'menu_items' => ['required'],
            ]);

            $this->updateMenuItems($request->menu_items);

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    private function updateMenuItems($items, $parent_id = null)
    {
        foreach ($items as $index => $item) {
            $menu_item = MenuItem::find($item['id']);
            $menu_item->order = $index + 1;
            $menu_item->parent_id = $parent_id;
            $menu_item->save();
            if (array_key_exists('children', $item) && count($item['children']) > 0) {
                $this->updateMenuItems($item['children'], $item['id']);
            }
        }
    }

    public function deleteMenu(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'menu_id' => ['required', 'exists:menus,id'],
            ]);

            Menu::find($request->menu_id)->delete();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function deleteMenuItem(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'menu_id'      => ['required', 'exists:menus,id'],
                'menu_item_id' => ['required', 'exists:menu_items,id'],
            ]);

            MenuItem::find($request->menu_item_id)->delete();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function getMenuItemPermissions(Request $request)
    {
        try {
            $request->validate([
                'menu_id'      => ['required', 'exists:menus,id'],
                'menu_item_id' => ['required', 'exists:menu_items,id'],
            ]);

            $menu_item = MenuItem::find($request->menu_item_id);

            $permissions = [];

            if ($menu_item) {
                $menu_item_permissions = explode(',', $menu_item->permissions);
                $permissions = Permission::all();
                $custom_permissions = [];
                foreach ($permissions as $index => $permission) {
                    if (in_array($permission->key, $menu_item_permissions)) {
                        $permission->selected = 1;
                    } else {
                        $permission->selected = 0;
                    }
                    $custom_permissions[] = $permission;
                }
            }

            $data['menu_item_permissions'] = $custom_permissions;

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function setMenuItemPermissions(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'menu_id'      => ['required', 'exists:menus,id'],
                'menu_item_id' => ['required', 'exists:menu_items,id'],
            ]);

            $permission_ids = $request->get('permissions', []);
            $permissions = Permission::whereIn('id', $permission_ids)->get();
            $permissions = collect($permissions)->pluck('key')->toArray();

            $menu_item = MenuItem::find($request->menu_item_id);
            $menu_item->permissions = count($permission_ids) > 0 ? implode(',', $permissions) : null;
            $menu_item->save();

            $data['menu_item'] = $menu_item;
            DB::commit();

            return ApiResponse::success($data);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
