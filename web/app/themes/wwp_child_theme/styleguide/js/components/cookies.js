(function () {

    var cookieComponent = function ($cookieWrap) {
        this.$wrap = $cookieWrap;
    };
    cookieComponent.prototype = {
        init: function () {
            var t = this;
            this.$wrap.find('button').on('click', function (e) {
                e.preventDefault();
                t.$wrap.removeClass('active');
                $(t.$wrap).bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function () {
                    t.$wrap.remove();
                });
            });
        }
    };

    //Styleguide
    var $styleGuideCookieComp = $('#cookies .component .cookies-wrap');
    if($styleGuideCookieComp.length) {
        new cookieComponent($styleGuideCookieComp);
    }

})();