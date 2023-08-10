<?php

namespace Uasoft\Badaso\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends Model
{
    use LogsActivity;

    protected $table = null;

    /**
     * Constructor for setting the table name dynamically.
     */
    public function __construct(array $attributes = [])
    {
        $prefix = config('badaso.database.prefix');
        $this->table = $prefix.'permissions';
        parent::__construct($attributes);
    }

    protected $guarded = [];

    public static function generateFor($table_name, $is_maintenance = false)
    {
        if (! Permission::where('table_name', $table_name)->first()) {
            $permissions = [];
            $permissions[] = self::firstOrCreate(['key' => 'browse_'.$table_name, 'description' => 'Browse '.$table_name, 'table_name' => $table_name, 'roles_can_see_all_data' => '["administrator"]', 'field_identify_related_user' => 'user_id']);
            $permissions[] = self::firstOrCreate(['key' => 'read_'.$table_name, 'description' => 'Read '.$table_name, 'table_name' => $table_name, 'roles_can_see_all_data' => '["administrator"]', 'field_identify_related_user' => 'user_id']);
            $permissions[] = self::firstOrCreate(['key' => 'edit_'.$table_name, 'description' => 'Edit '.$table_name, 'table_name' => $table_name, 'roles_can_see_all_data' => '["administrator"]', 'field_identify_related_user' => 'user_id']);
            $permissions[] = self::firstOrCreate(['key' => 'add_'.$table_name, 'description' => 'Add '.$table_name, 'table_name' => $table_name, 'roles_can_see_all_data' => '["administrator"]', 'field_identify_related_user' => 'user_id']);
            $permissions[] = self::firstOrCreate(['key' => 'delete_'.$table_name, 'description' => 'Delete '.$table_name, 'table_name' => $table_name, 'roles_can_see_all_data' => '["administrator"]', 'field_identify_related_user' => 'user_id']);

            if ($is_maintenance) {
                $permissions[] = self::firstOrCreate(['key' => 'maintenance_'.$table_name, 'description' => 'Maintenance '.$table_name, 'table_name' => $table_name, 'roles_can_see_all_data' => '["administrator"]', 'field_identify_related_user' => 'user_id']);
            }

            $administrator = Role::where('name', 'administrator')->firstOrFail();

            if (! is_null($administrator)) {
                foreach ($permissions as $row) {
                    $role_permission = RolePermission::where('role_id', $administrator->id)
                            ->where('permission_id', $row->id)
                            ->first();
                    if (is_null($role_permission)) {
                        $role_permission = new RolePermission();
                        $role_permission->role_id = $administrator->id;
                        $role_permission->permission_id = $row->id;
                        $role_permission->save();
                    }
                }
            }
        }
    }

    public static function removeFrom($table_name)
    {
        $permissions = self::where(['table_name' => $table_name])->get();
        $permissions = collect($permissions)->pluck('id')->toArray();
        RolePermission::whereIn('permission_id', $permissions)->delete();
        self::where(['table_name' => $table_name])->delete();
    }

    protected static $logAttributes = true;
    protected static $logFillable = true;
    protected static $logName = 'Permission';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    /**
     * The roles that belong to the Permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, config('badaso.database.prefix').'role_permissions');
    }

    /**
     * Get the role_permission that owns the Permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role_permission()
    {
        return $this->belongsTo(RolePermission::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->dontSubmitEmptyLogs();
    }

    public static function generateForTableCRUD()
    {
        $get_all_table_name = DataType::all();
        foreach ($get_all_table_name as $key => $table) {
            $permission_table_name = Permission::where('table_name', $table->name)->get();
            foreach ($permission_table_name as $key => $table_name) {
                if ($table->roles_can_see_all_data == null && $table_name->field_identify_related_user == null) {
                    $table_name->roles_can_see_all_data = '["administrator"]';
                    $table_name->field_identify_related_user = 'user_id';
                    $table_name->save();
                }
            }
        }
    }
}
