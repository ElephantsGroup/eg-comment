To use Elephants Group comment module first you must install module, then you can use comment widget anywhere in your website.

Installation Steps:
===

1) run
> php composer.phar require elephantsgroup/yii2-comment "*"

or add `"elephantsgroup/yii2-comment": "*"` to the require section of your composer.json file.

2) migrate database
> yii migrate --migratiocommentnPath=vendor/elephantsgroup/yii2-comment/migrations

3) add comment module to common configuration (common/config.php file)

```'modules' => [
    ...
    'comment' => [
        'class' => 'elephantsGroup\comment\Module',
    ],
    ...
]```

4) open access to module in common configuration

```'as access' => [
    'class' => 'mdm\admin\components\AccessControl',
    'allowActions' => [
        ...
        'comment/ajax/*',
        ...
    ]
]```

5) filter admin controller in frontend configuration (frontend/config.php file)

```'modules' => [
    ...
    'comment' => [
        'as frontend' => 'elephantsGroup\comment\filters\FrontendFilter',
    ],
    ...
]```

5) filter ajax controller in backend configuration (backend/config.php file)

```'modules' => [
    ...
    'comment' => [
        'as backend' => 'elephantsGroup\comment\filters\BackendFilter',
    ],
    ...
]```

Using comment widget
===

Anywhere in your code you can use comment widget as follows:
```<?= Comments::widget() ?>```

You need to use Comments widget header in your page:
```use elephantsGroup\comment\components\Comments;```

Comment widget parameters
---

- item (integer): to separate comments between different items.
```<?= Comments::widget(['item' => 1]) ?>```
```<?= Comments::widget(['item' => $model->id]) ?>```

default value for item is 0
- service (integer): to separate comments between various item types.
```<?= Comments::widget(['service' => 1, 'item' => $model->id]) ?>```

for example you can use different values for different modules in your app, and then use comment widget separately in modules.
default value for service is 0

- enabled_name (boolean): show name in comment form or not, default true
```<?= Comments::widget([
    'service' => 1,
    'item' => $model->id,
    'enabled_name' => false,
    'view_file' => Yii::getAlias('@frontend') . '/views/comment/widget.php'
]) ?>```

- enabled_subject (boolean): show subject in comment form or not, default true
```<?= Comments::widget([
    'service' => 1,
    'item' => $model->id,
    'enabled_name' => false,
    'enabled_subject' => true,
    'view_file' => Yii::getAlias('@frontend') . '/views/comment/widget.php'
]) ?>```

- enabled_description (boolean): show description in comment form or not, default true
```<?= Comments::widget([
    'service' => 1,
    'item' => $model->id,
    'enabled_name' => false,
    'enabled_subject' => true,
    'enabled_description' => true,
    'view_file' => Yii::getAlias('@frontend') . '/views/comment/widget.php'
]) ?>```

- view_file (string): the view file path for rendering

```<?= Comments::widget([
    'service' => 1,
    'item' => $model->id,
    'color' => 'yellow',
    'view_file' => Yii::getAlias('@frontend') . '/views/comment/widget.php'
]) ?>```

you can use these variables in your customized view:
* service
* item
* enabled_name
* enabled_subject
* enabled_description
