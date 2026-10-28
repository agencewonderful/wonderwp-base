<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/08/2016
 * Time: 11:08
 */
function wwp_theme_setup()
{
    \WonderWp\Plugin\Core\Http\SessionHelper::ensureStarted(true);
    get_template_part('/includes/ThemeManager');
    get_template_part('/includes/ChildThemeManager');
    $setupManager = new WonderWp\Theme\Child\ChildThemeManager();
    $setupManager->run();
}

if(!function_exists('trad')) {
    function trad($key, $domain = WWP_THEME_TEXTDOMAIN) {
        return __($key, $domain);
    }
}

function getSvgIcon($iconName)
{
    return \WonderWp\Theme\Child\Service\ChildThemeShortcodeService::getSvgIcon($iconName);
}

function isEditorContext()
{
    return !empty($_GET) && isset($_GET['context']) && $_GET['context'] === 'edit' && !empty($_GET['_locale']) && $_GET['_locale'] === 'user';
}
