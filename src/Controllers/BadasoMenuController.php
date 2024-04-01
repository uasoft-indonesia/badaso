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
            $menus = Menu::orderBy('order')->get();

            $data['menus'] = $menus;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function menuOptions(Request $request)
    {
        try {
            if (isset($request->order)) {
                $request->validate([
                    'order' => ['array'],
                ]);

                foreach ($request->order as $index => $menu_id) {
                    $menu = Menu::find($menu_id);
                    $menu->order = $index + 1;
                    $menu->save();
                }
            } elseif (isset($request->is_expand)) {
                $type = $request->get('type', 'menu');
                switch ($type) {
                    case 'menu_item':
                        $request->validate([
                            'menu_item_id' => ['required', 'integer'],
                            'is_expand' => ['boolean'],
                        ]);

                        $menu_item_id = $request->menu_item_id;
                        $menu_item = MenuItem::find($menu_item_id);

                        $is_expand = $request->get('is_expand', ! $menu_item->is_expand);
                        $menu_item->is_expand = $is_expand;
                        $menu_item->save();
                        break;
                    default:
                        $request->validate([
                            'menu_id' => ['required', 'integer'],
                            'is_expand' => ['boolean'],
                        ]);

                        $menu_id = $request->menu_id;
                        $menu = Menu::find($menu_id);

                        $is_expand = $request->get('is_expand', ! $menu->is_expand);
                        $menu->is_expand = $is_expand;
                        $menu->save();
                        break;
                }
            } elseif (isset($request->is_show_header)) {
                $request->validate([
                    'menu_id' => ['required', 'integer'],
                ]);

                $menu = Menu::find($request->menu_id);
                $menu->is_show_header = ! $menu->is_show_header;
                $menu->save();
            }

            return ApiResponse::success();
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
            $prefix = config('badaso.database.prefix');

            $menu_items = MenuItem::where('menu_id', $request->menu_id)
                ->orderBy('order', 'asc')
                ->whereNull($prefix.'menu_items.parent_id')
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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'menu_item_id' => ['required', 'exists:Uasoft\Badaso\Models\MenuItem,id'],
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
                'menu_key' => ['required', 'exists:Uasoft\Badaso\Models\Menu,key'],
            ]);
            $prefix = config('badaso.database.prefix');
            $menu = Menu::where('key', $request->menu_key)->first();

            $all_menu_items = MenuItem::join($prefix.'menus', $prefix.'menus.id', $prefix.'menu_items.menu_id')
                ->where($prefix.'menus.key', $request->menu_key)
                ->whereNull($prefix.'menu_items.parent_id')
                ->select($prefix.'menu_items.*')
                ->orderBy($prefix.'menu_items.order', 'asc')
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
                'menu_key' => ['string'],
            ]);
            $prefix = config('badaso.database.prefix');
            if (isset($request->menu_key)) {
                $menu_keys = explode(',', $request->menu_key);
                foreach ($menu_keys as $key => $menu_key) {
                    $menu = Menu::where('key', $menu_key)->first();

                    $all_menu_items = MenuItem::join($prefix.'menus', $prefix.'menus.id', $prefix.'menu_items.menu_id')
                        ->where($prefix.'menus.key', $menu_key)
                        ->whereNull($prefix.'menu_items.parent_id')
                        ->select($prefix.'menu_items.*')
                        ->orderBy($prefix.'menu_items.order', 'asc')
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

                $data = collect($data)->toArray();
            } else {
                $all_menu_items = MenuItem::orderBy($prefix.'menu_items.order', 'asc')
                    ->get();
                $menus = Menu::orderBy('order')->get();

                $data = [];
                // variable function to search and call child menu item
                $menu_children = function ($collection, $menu_children_callback) use ($all_menu_items) {
                    return $collection->map(function ($item) use ($all_menu_items, $menu_children_callback) {
                        $children = $all_menu_items->where('parent_id', $item->id);

                        if (count($children) > 0) {
                            $children = $menu_children_callback($children, $menu_children_callback);

                            $item->children = array_values($children->toArray());
                        }

                        return $item;
                    })->filter(function ($menu_item) {
                        $allowed = AuthenticatedUser::isAllowedTo($menu_item->permissions);

                        return $allowed;
                    });
                };
                foreach ($menus as $index => $menu) {
                    $menu_items = $all_menu_items
                        ->where('parent_id', null)
                        ->where('menu_id', $menu->id);

                    $menu_items = $menu_children($menu_items, $menu_children);

                    $menu_items = $menu_items->toArray();
                    $data[$index] = [
                        'menu' => $menu,
                        'menu_items' => array_values($menu_items),
                    ];
                }
            }

            return ApiResponse::success($data);
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
                'key' => ['required', 'unique:Uasoft\Badaso\Models\Menu'],
                'display_name' => ['required'],
            ]);

            $new_menu = new Menu();
            $new_menu->key = $request->get('key');
            $new_menu->display_name = $request->get('display_name');
            $new_menu->icon = $request->get('icon');
            $new_menu->save();

            DB::commit();
            activity('Menu')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $new_menu])
                ->performedOn($new_menu)
                ->event('created')
                ->log('Menu '.$new_menu->display_name.' has been created');

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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'title' => ['required'],
                'url' => ['required'],
                'target' => ['required'],
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
            activity('Menu')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $new_menu_item])
                ->performedOn($new_menu_item)
                ->event('created')
                ->log('Menu '.$new_menu_item->title.' has been created');

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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'key' => ['required', "unique:Uasoft\Badaso\Models\Menu,key,{$request->menu_id}"],
                'display_name' => ['required'],
            ]);

            $menu = Menu::find($request->menu_id);
            $old_menu = $menu;
            $menu->key = $request->get('key');
            $menu->display_name = $request->get('display_name');
            $menu->icon = $request->get('icon');
            $menu->save();

            DB::commit();
            activity('Menu')
                ->causedBy(auth()->user() ?? null)
                ->withProperties([
                    'attributes' => [
                        'old' => $old_menu,
                        'new' => $menu,
                    ],
                ])
                ->performedOn($menu)
                ->event('updated')
                ->log('Menu '.$menu->display_name.' has been updated');

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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'menu_item_id' => ['required', 'exists:Uasoft\Badaso\Models\MenuItem,id'],
                'title' => ['required'],
                'url' => ['required'],
                'target' => ['required'],
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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'menu_item_id' => ['required', 'exists:Uasoft\Badaso\Models\MenuItem,id'],
            ]);
            $menu_item = MenuItem::find($request->menu_item_id);
            $old_menu_item = $menu_item->toArray();
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
            activity('Menu')
                ->causedBy(auth()->user() ?? null)
                ->withProperties([
                    'attributes' => [
                        'old' => $old_menu_item,
                        'new' => $menu_item,
                    ],
                ])
                ->performedOn($menu_item)
                ->event('updated')
                ->log('Menu item '.$menu_item->title.' has been updated');

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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'menu_items' => ['required'],
            ]);

            $this->updateMenuItems($request->menu_items);

            DB::commit();
            activity('Menu')
                ->causedBy(auth()->user() ?? null)
                ->event('updated')
                ->log('Menu item order  has been updated');

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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
            ]);

            $menus = Menu::find($request->menu_id);
            $menus->delete();
            DB::commit();
            activity('Menu')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $request->all()])
                ->performedOn($menus)
                ->event('deleted')
                ->log('Menu '.$menus->display_name.' has been deleted');

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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'menu_item_id' => ['required', 'exists:Uasoft\Badaso\Models\MenuItem,id'],
            ]);

            $menu_items = MenuItem::find($request->menu_item_id);
            $menu_items->delete();

            DB::commit();
            activity('Menu')
                ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $request->all()])
                ->performedOn($menu_items)
                ->event('deleted')
                ->log('Menu item'.$menu_items->title.' has been deleted');

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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'menu_item_id' => ['required', 'exists:Uasoft\Badaso\Models\MenuItem,id'],
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
                'menu_id' => ['required', 'exists:Uasoft\Badaso\Models\Menu,id'],
                'menu_item_id' => ['required', 'exists:Uasoft\Badaso\Models\MenuItem,id'],
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
