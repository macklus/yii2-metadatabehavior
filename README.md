# yii2-metadatabehavior

MetaDataBehavior allow to use one TEXT field on a database as a json data
 To use MetaDataBehavior, simply add this behavior into your behaviors model function

```php
use macklus\MetaDataBehavior\MetaDataBehavior;

public function behaviors()
{
    return [
        [
            'class' => MetaDataBehavior::className(),
            'attribute' => 'metadata',
        ],
    ];
}
```

Your model table should have a TEXT field named as attribute property

Then, in your controller, you can user getMetaData(keyword,default) and setMetaData(keyword, value)

```php
$model = MyModel::find()->where(['id' => 1])->one();
$model->setMetaData('keyword1','value1');
$model->setMetaData('otherkeyword','anothervalue');

// Other stuff
echo $model->getMetaData('keyword1');

@author José Pedro Andrés <macklus@debianitas.net>
@since 2.0Yii2 MetaData Behavior
