<div id="page" class="hfeed site">
    <div class="skip-links"><a href="#content"><?php echo trad('Skip to content', WWP_THEME_TEXTDOMAIN); ?></a></div>

    <?php echo apply_filters('wwp_before_header', ''); ?>

    <header class="site-header" id="header" role="banner">

        <div class="inner-header">

            <button class="wdf-burger nav-button" data-menu-toggler type="button" aria-label="open/close navigation"><i></i></button>

            <?php
            echo '<a href="/" class="logo" aria-label="' . trad('back.to.home', WWP_THEME_TEXTDOMAIN) . '"><img src="' . apply_filters('wwp-header-logo', '/app/themes/wwp_child_theme/assets/raw/images/logo-site.svg') . '" alt="Mon site - accueil" height="=44" width="200"></a>'
            ?>

            <nav role="navigation" aria-label="<?php echo trad('main.menu', WWP_THEME_TEXTDOMAIN); ?>" class="<?php echo apply_filters('wwp-main-nav-class', 'navigation-wrapper'); ?>">

                <ul class="header-menu" id="menu">
                    <?php
                    /** @var \WonderWp\Theme\Core\Service\ThemeViewService $viewService */
                    $viewService = wwp_get_theme_service('view');
                    $exclude     = [];
                    echo $viewService->getMainMenu($exclude);
                    ?>
                </ul>

            </nav>

            <?php
            /** @var \WonderWp\Theme\Core\Service\ThemeViewService $themeViewService */
            $themeViewService = wwp_get_theme_service('view');
            echo $langSwitcher = $themeViewService->getLangSwitcher(
                __DIR__ . '/plugins/wwp-translator/public/views/LangSwitcher.php',
                false,
                true
            );
            ?>

        </div>
    </header>
    <?php echo apply_filters('wwp_after_header', ''); ?>

    <div id="content" class="site-content transitionning">
        <?php echo apply_filters('wwp_prepend_content', ''); ?>
