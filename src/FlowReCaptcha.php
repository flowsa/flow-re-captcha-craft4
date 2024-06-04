<?php
/**
 * FlowReCaptcha plugin for Craft CMS 3.x
 *
 * A re-captcha field type for validating forms
 *
 * @link      https://www.flowsa.com
 * @copyright Copyright (c) 2018 Flow Communications
 */

namespace flowsa\flowrecaptcha;

use craft\events\RegisterTemplateRootsEvent;
use craft\web\View;
use flowsa\flowrecaptcha\models\Settings;
use flowsa\flowrecaptcha\fields\FlowReCaptchaField as FlowReCaptchaFieldField;

use Craft;
use craft\base\Plugin;

use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use craft\guestentries\controllers\SaveController;
use craft\guestentries\events\SaveEvent;
use yii\base\Event;

/**
 * Class FlowReCaptcha
 *
 * @author    Flow Communications
 * @package   FlowReCaptcha
 * @since     0.0.1
 *
 */
class FlowReCaptcha extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var FlowReCaptcha
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = '0.0.2';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = FlowReCaptchaFieldField::class;
            }
        );

        if (!class_exists(SaveController::class)) {
            return;
        }

        Event::on(
            SaveController::class, 
            SaveController::EVENT_BEFORE_SAVE_ENTRY, 
            function(SaveEvent $e) {
            $settings = $this->getSettings();

            if (!$settings->honeypotParam) {
                Craft::warning('Couldn\'t check honeypot field because the "Honeypot Form Param" setting isn\'t set yet.');
                return;
            }
            $request = Craft::$app->getRequest();
            $fields = $request->getBodyParam('fields');
            $val = $request->getBodyParam($settings->honeypotParam);

            if (array_key_exists($settings->honeypotParam, $fields)) {
                $val = $fields[$settings->honeypotParam];
            }

            if ($val === null) {
                Craft::warning('Couldn\'t check honeypot field because no POST parameter named "'.$settings->honeypotParam.'" exists.');
                return;
            }

            // All conditions are favorable
            if ($val !== '') {
                $e->isSpam = true;
            }
        });

       
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        // Get the settings that are being defined by the config file
        $overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($this->id));

        return Craft::$app->view->renderTemplate(
            'flow-re-captcha/settings',
            [
                'settings' => $this->getSettings(),
                'overrides' => array_keys($overrides)
            ]
        );
    }
}
