<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 19/09/2016
 * Time: 16:24
 */

namespace WonderWp\Plugin\Newsletter;


abstract class AbstractPasserelle implements PasserelleInterface
{
    public function getOptions()
    {
        return array();
    }
}