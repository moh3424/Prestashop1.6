<?php

class ModelProposerProduit extends ObjectModel
{
    public $name;
    public $title;
    public $active = false;
    public $description;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'nouveauproduit',
        'primary' => 'id_nouveauproduit',
        'multilang' => true,
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 255),
            //'image_file' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'),
            'active' => array('type' => self::TYPE_BOOL),

            /* Lang fields */
            'title' =>        array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'),
            'description' =>    array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'),
        ),
    );
    public static function getImgPath($front = false)
    {
        return $front ? _PS_IMG_ . 'nouveauproduit/' : _PS_IMG_DIR_ . 'nouveauproduit/';
    }
    // public static function getImgPath()
    // {
    //     return  _PS_IMG_DIR_ . 'nouveauproduit/';
    // }
    public static $img_dir = _PS_IMG_DIR_ . 'nouveauproduit';

    public static function getProposerProduit($limit = 6, $active = true, $id_lang = null)
    {
        $id_lang = $id_lang ? $id_lang : Context::getContext()->language->id;
        $q = new DbQuery();
        $q->select('n.*, nl.title, nl.description')
            ->from('nouveauproduit', 'n')
            ->innerJoin('nouveauproduit_lang', 'nl', 'nl.id_nouveauproduit=n.id_nouveauproduit and nl.id_lang=' . $id_lang)
            ->limit($limit)
            ->where('n.active=' . (int) $active);
        return Db::getInstance()->executeS($q);
    }
}
