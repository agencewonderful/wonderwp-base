public function activate()
{
    $this->setupOverride([
        'child_namespace'           => 'WonderWp\Plugin\Actu\Child',
        'parent_manager_use'        => 'WonderWp\Plugin\Actu\ActuManager',
        'child_manager_class_name'  => 'ActuThemeManager',
        'parent_manager_class_name' => 'ActuManager',
        'psr4_namespace'            => "WonderWp\\Plugin\\Actu\\Child\\",
        'override_dir'              => "web/app/themes/" . str_replace('%2F', '/', rawurlencode(get_stylesheet())) . "/plugins/wwp-actu",
        'manager_constant_name'     => 'WWP_PLUGIN_ACTU_MANAGER',
    ]);
}
