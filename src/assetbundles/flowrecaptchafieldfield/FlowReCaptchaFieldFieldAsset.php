<?php
/**
 * FlowReCaptcha plugin for Craft CMS 3.x
 *
 * A re-captcha field type for validating forms
 *
 * @link      https://www.flowsa.com
 * @copyright Copyright (c) 2018 Flow Communications
 */

namespace flowsa\flowrecaptcha\assetbundles\flowrecaptchafieldfield;

use Craft;
use craft\web\AssetBundle;
use yii\web\JqueryAsset;
// use craft\web\assets\cp\CpAsset;

/**
 * @author    Flow Communications
 * @package   FlowReCaptcha
 * @since     0.0.1
 */
class FlowReCaptchaFieldFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@flowsa/flowrecaptcha/assetbundles/flowrecaptchafieldfield/dist";

        $this->depends = [
            JqueryAsset::class,
        ];

        $this->js = [
            'js/FlowReCaptchaField.js',
        ];

        $this->css = [
            'css/FlowReCaptchaField.css',
        ];

        parent::init();
    }
}
