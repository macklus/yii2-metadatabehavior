<?php

/**
 * @link https://github.com/macklus/yii2-metadatabehavior
 * @copyright Copyright (c) 2016 Macklus
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace macklus\MetaDataBehavior;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\InvalidCallException;

/**
 * MetaDataBehavior allow to use one TEXT field on a database as a json data
 *
 * To use MetaDataBehavior, simply add this behavior into your behaviors model function
 *
 * ```php
 * use macklus\MetaDataBehavior\MetaDataBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => MetaDataBehavior::className(),
 *             'attribute' => 'metadata',
 *         ],
 *     ];
 * }
 * ```
 *
 * Your model table should have a metadata TEXT field
 *
 * Then, in your controller, you can user getMetaData(keyword,default) and setMetaData(keyword, value)
 *
 * ```php
 * $model = MyModel::find()->where(['id' => 1])->one();
 * $model->setMetaData('keyword1','value1');
 * $model->setMetaData('otherkeyword','anothervalue');
 * $model->delMetaData('keyword');
 *
 * // Other stuff
 * echo $model->getMetaData('keyword1');
 *
 * @author José Pedro Andrés <macklus@debianitas.net>
 * @since 2.0
 */
class MetaDataBehavior extends Behavior
{

    public $attribute = 'metadata';
    private $_metaData = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    public function afterFind($event)
    {
        $this->_metaData = json_decode($this->owner->metadata, true);
    }

    public function getMetaData($key, $default = false)
    {
        if (isset($this->_metaData[$key])) {
            return $this->_metaData[$key];
        } else {
            return $default;
        }
    }

    public function setMetaData($key, $value = false)
    {
        $this->_metaData[$key] = $value;
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        if ($owner->getIsNewRecord()) {
            throw new InvalidCallException('Call setMetaData is not possible on a new record.');
        }
        $owner->updateAttributes(["$this->attribute" => json_encode($this->_metaData)]);
    }

	public function delMetaData($key)
    {
        $owner = $this->owner;
        unset($this->_metaData[$key]);
        $owner->updateAttributes(["$this->attribute" => json_encode($this->_metaData)]);
    }
}
