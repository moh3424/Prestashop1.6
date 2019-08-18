<?php

if (!class_exists('ModelProposerProduit'));
require_once _PS_MODULE_DIR_ . 'nouveauproduit/classes/ModelProposerProduit.php';

class AdminProposerProduitController extends ModuleAdminController
{

    public function __construct()
    {
        $this->table = 'nouveauproduit';
        $this->className = 'ModelProposerProduit';
        $this->lang = true;
        $this->bootstrap = true;
        $this->fieldImageSettings = array(
            'name' => 'logo',
            'dir' => 'nouveauproduit'
        );
        $this->fields_list = array(
            'id_nouveauproduit' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'logo' => array(
                'title' => $this->l('Logo'),
                'image' => 'nouveauproduit',
                'orderby' => false,
                'search' => false,
                'align' => 'center',
            ),

            'name' => array(
                'title' => $this->l('Nom'),
                'width' => 'auto'
            ),
            'title' => array(
                'title' => $this->l('Titre'),
                'width' => 'auto'
            ),
            'description' => array(
                'title' => $this->l('Déscription'),
                'width' => 'auto'
            ),

            // 'active' => array(
            //     'title' => $this->l('Enabled'),
            //     'active' => 'status',
            //     'type' => 'bool',
            //     'align' => 'center',
            //     'class' => 'fixed-width-xs',
            //     'orderby' => false
            // )
        );
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        parent::__construct();
    }

    public function renderForm()
    {
        if (!($nouveauproduit = $this->loadObject(true))) {
            return;
        }

        $image = ModelProposerProduit::$img_dir . '/' . $nouveauproduit->id . '.jpg';
        $image_url = ImageManager::thumbnail(
            $image,
            $this->table . '_' . (int) $nouveauproduit->id . '.' . $this->imageType,
            350,
            $this->imageType,
            true,
            true
        );
        $image_size = file_exists($image) ? filesize($image) / 1000 : false;

        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Nouveauproduit'),
                'icon' => 'icon-certificate'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Nom'),
                    'name' => 'name',
                    'col' => 4,
                    'required' => true,
                    'hint' => $this->l('Invalid characters:') . ' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Titre'),
                    'name' => 'title',
                    'required' => true,
                    'lang' => true,
                    'cols' => 60,
                    'rows' => 10,
                    'autoload_rte' => 'rte', //Enable TinyMCE editor for short description
                    'col' => 6,
                    'hint' => $this->l('Invalid characters:') . ' &lt;&gt;;=#{}'
                ),

                array(
                    'type' => 'textarea',
                    'label' => $this->l('Déscription'),
                    'name' => 'description',
                    'lang' => true,
                    'cols' => 60,
                    'rows' => 10,
                    'col' => 6,
                    'autoload_rte' => 'rte', //Enable TinyMCE editor for description
                    'hint' => $this->l('Invalid characters:') . ' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Logo'),
                    'name' => 'logo',
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size,
                    'display_image' => true,
                    'col' => 6,
                    'hint' => $this->l('Upload a manufacturer logo from your computer.')
                ),

                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                )
            )
        );

        if (!($nouveauproduit = $this->loadObject(true))) {
            return;
        }
        $this->fields_form['submit'] = array(
            'title' => $this->l('Save')
        );

        foreach ($this->_languages as $language) {
            $this->fields_value['description_' . $language['id_lang']] = htmlentities(stripslashes($this->getFieldValue(
                $nouveauproduit,
                'description',
                $language['id_lang']
            )), ENT_COMPAT, 'UTF-8');
        }

        return parent::renderForm();
    }
}
