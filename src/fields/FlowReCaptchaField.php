<?php
/**
 * FlowReCaptcha plugin for Craft CMS 3.x
 *
 * A re-captcha field type for validating forms
 *
 * @link      https://www.flowsa.com
 * @copyright Copyright (c) 2018 Flow Communications
 */

namespace flowsa\flowrecaptcha\fields;

use flowsa\flowrecaptcha\FlowReCaptcha;
use flowsa\flowrecaptcha\assetbundles\flowrecaptchafieldfield\FlowReCaptchaFieldFieldAsset;

use Craft;
use craft\base\ElementInterface;

use craft\base\Field;
use GuzzleHttp\Client;
use yii\db\Schema;

use craft\helpers\Html;

/**
 * @author    Flow Communications
 * @package   FlowReCaptcha
 * @since     0.0.1
 */
class FlowReCaptchaField extends Field
{
    // Public Properties
    // =========================================================================

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('flow-re-captcha', 'FlowReCaptchaField');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'flow-re-captcha/_components/fields/FlowReCaptchaField_settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml(mixed $value, ?\craft\base\ElementInterface $element = null): string
    {
        Craft::$app->getView()->registerAssetBundle(FlowReCaptchaFieldFieldAsset::class);
        $settings = FlowReCaptcha::$plugin->settings;
        $id = Html::id($this->handle);

        $id = $settings->honeypotParam ?: $id;

        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        return Craft::$app->getView()->renderTemplate('_includes/forms/text', [
            'type' => 'text',
            'id' => $id,
            'instructionsId' => "$id-instructions",
            'name' => $id,
            'value' => '',
            'fieldLabel' => '',
            'namespace' => $namespacedId,
        ]);


        // // Register our asset bundle



        // //Craft::$app->templates->includeJsResource('recaptcha/js/scripts.js');
        // Craft::$app->getView()->registerJsFile('https://www.google.com/recaptcha/api.js');

        // // Get our id and namespace
        // $id           = Craft::$app->getView()->formatInputId($this->handle);
        // $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // // Variables to pass down to our field JavaScript to let it namespace properly
        // $jsonVars = [
        //     'id'        => $id,
        //     'name'      => $this->handle,
        //     'namespace' => $namespacedId,
        //     'prefix'    => Craft::$app->getView()->namespaceInputId(''),
        // ];
        // $jsonVars = Json::encode($jsonVars);
        // Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').FlowReCaptchaFlowReCaptchaField(" . $jsonVars . ");");

        // // Render the input template
        // return Craft::$app->getView()->renderTemplate(
        //     'flow-re-captcha/_components/fields/FlowReCaptchaField_input',
        //     [
        //         'name'         => $this->handle,
        //         'value'        => $value,
        //         'field'        => $this,
        //         'id'           => $id,
        //         'namespacedId' => $namespacedId,
        //         'siteKey'      => FlowReCaptcha::$plugin->getSettings()->siteKey,
        //     ]
        // );
    }

    // public function _validate()
    // {
    //     $captcha = Craft::$app->request->post('g-recaptcha-response');
    //     $user = Craft::$app->getUser();
    //     $request = Craft::$app->getRequest();

    //     if ($request->getIsCpRequest() || $user || !isset($captcha)) {
    //         return true;
    //     }

    //     $verified = $this->verify($captcha);

    //     return $verified;
    // }

    // public function getElementValidationRules(): array
    // {
    //     //$this->handle
    //     return [
    //         [$this->handle, 'skipOnEmpty' => false, function ($model, $params) {
    //             if (!$this->_validate()) {
    //                 $model->addError($this->handle, 'The CAPTCHA verification failed.');
    //                 return false;
    //             }
    //         }]
    //     ];
    // }

    public function verify($data)
    {
        $base = "https://www.google.com/recaptcha/api/siteverify";

        $params = array(
            'secret'   => FlowReCaptcha::$plugin->getSettings()->secretKey,
            'response' => $data
        );

        $client = new Client();

        $response = $client->request('POST', $base, [
            'form_params' => $params
        ]);

        if ($response->getStatusCode() == 200) {
            $json = json_decode((string)$response->getBody(), true);
            if ($json['success']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
