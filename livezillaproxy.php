<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class LivezillaProxy
 */
class LivezillaProxy extends Module
{
    const API_LOCATION = 'LIVEZILLA_API_LOC';
    const API_USER = 'LIVEZILLA_API_USER';
    const API_PASSWORD = 'LIVEZILLA_API_PASSWD';

    /**
     * BeesBlog constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->name = 'livezillaproxy';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'thirty bees';

        $this->controllers = ['knowledgebase'];
        $this->bootstrap = true;

        parent::__construct();
        $this->displayName = $this->l('Livezilla proxy');
        $this->description = $this->l('');
    }

    /**
     * Install this module
     *
     * @return bool Whether the module has been successfully installed
     *
     * @since 1.0.0
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $this->registerHook('moduleRoutes');

        return true;
    }

    /**
     * Uninstall this module
     *
     * @return bool Whether the module has been successfully uninstalled
     *
     * @since 1.0.0
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $this->postProcess();

        return $this->generateCredentialsForm();
    }

    protected function postProcess()
    {
        if (Tools::getValue('submitCredentials')) {
            Configuration::updateValue(self::API_LOCATION, Tools::getValue(self::API_LOCATION));
            Configuration::updateValue(self::API_USER, Tools::getValue(self::API_USER));
            Configuration::updateValue(self::API_PASSWORD, Tools::getValue(self::API_PASSWORD));
        }
    }

    /**
     * @return string
     *
     * @since 1.0.0
     */
    protected function generateCredentialsForm()
    {
        $fields = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Livezilla API Credentials'),
                    'icon'  => 'icon-server',
                ],
                'input'  => [
                    [
                        'type'  => 'text',
                        'label' => $this->l('API location'),
                        'name'  => self::API_LOCATION,
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('API User'),
                        'name'  => self::API_USER,
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('API Password'),
                        'name'  => self::API_PASSWORD,
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = '';
        $helper->submit_action = 'submitCredentials';
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
        ];

        return $helper->generateForm([$fields]);
    }

    protected function getConfigFieldsValues()
    {
        return [
            self::API_LOCATION => Configuration::get(self::API_LOCATION),
            self::API_USER     => Configuration::get(self::API_USER),
            self::API_PASSWORD => Configuration::get(self::API_PASSWORD),
        ];
    }

    /**
     * Register the module routes
     *
     * @return array Array with routes
     */
    public function hookModuleRoutes()
    {
        return [
            'knowledgebase' => [
                'controller' => 'knowledgebase',
                'rule'       => 'knowledgebase.php',
                'keywords'   => [],
                'params'     => [
                    'fc'     => 'module',
                    'module' => $this->name,
                ],
            ],
        ];
    }
}
