<?php

/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2019 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
if (!class_exists('ModelProposerProduit'));
require_once _PS_MODULE_DIR_ . 'nouveauproduit/classes/ModelProposerProduit.php';

class Nouveauproduit extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'nouveauproduit';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Mohamed';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('proposerproduit');
        $this->description = $this->l('Mon module qui permet à l\'utilisateur connecté de proposer un produit');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     *fonction pour installer
     */
    public function install()
    {
        require _PS_MODULE_DIR_ . 'nouveauproduit/sql/install.php';

        return parent::install() &&
            $this->registerHook('header') &&
            $this->installFolder() && // pour installer un repertoir
            $this->registerHook('displayHome') && // accroché le module sur le front
            $this->registerHook('backOfficeHeader') &&
            $this->installTab(true);
    }

    /**
     * fonction pour désinstaller
     */
    public function uninstall()
    {
        require _PS_MODULE_DIR_ . 'nouveauproduit/sql/uninstall.php';
        return $this->installTab(false) && parent::uninstall();
    }

    /**
     *  Méthode pour récupérer tous les produits actifs
     */
    public function installTab($install = true)
    {
        if ($install) {
            $languages = language::getLanguages();
            $tab = new Tab();
            $tab->module = $this->name;
            $tab->class_name = 'AdminProposerProduit';
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = $this->name;
            }
            return $tab->save();
        } else {
            $id = Tab::getIdFromClassName('AdminProposerProduit');
            if ($id) {
                $tab = new Tab($id);
                return $tab->delete();
            }
            return true;
        }
    }

    /**
     * Installer un repertoir
     */
    public function installFolder()
    {
        if (!file_exists(ModelProposerProduit::$img_dir)) {
            return mkdir(ModelProposerProduit::$img_dir, '0777'); //création d'un repertoir 
        }
        return true;
    }


    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool) Tools::isSubmit('submitNouveauproduitModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitNouveauproduitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l(''),
                        'name' => 'NBR_NOUVEAUPRODUIT_HOME',
                        'label' => $this->l('Nombre de produit proposition sur la home'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'NBR_NOUVEAUPRODUIT_HOME' => Configuration::get('NBR_NOUVEAUPRODUIT_HOME'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    /**
     * Création de la fonction hookDisplayHome pour évité de réinstaller le module
     */
    public function hookDisplayHome()
    {
        $nouveauproduits = ModelProposerProduit::getProposerProduit(Configuration::get('NBR_NOUVEAUPRODUIT_HOME'), true);

        $this->context->smarty->assign(array(
            'nouveauproduits' => $nouveauproduits,
            'nouveauproduit_path' => ModelProposerProduit::getImgPath(true)
        ));
        return $this->display(__FILE__, 'nouveauproduit_home.tpl');
    }
}
