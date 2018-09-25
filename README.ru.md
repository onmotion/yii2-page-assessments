# Модуль оценки/голосования для Yii2
Модуль оценки страницы/голосования для Yii2 основанный на Vue2.

Язык: [English](README.md), [Русский](README.ru.md)

[![Latest Stable Version](https://poser.pugx.org/onmotion/yii2-page-assessments/v/stable)](https://packagist.org/packages/onmotion/yii2-page-assessments)
[![Total Downloads](https://poser.pugx.org/onmotion/yii2-page-assessments/downloads)](https://packagist.org/packages/onmotion/yii2-page-assessments)
[![Monthly Downloads](https://poser.pugx.org/onmotion/yii2-page-assessments/d/monthly)](https://packagist.org/packages/onmotion/yii2-page-assessments)
[![License](https://poser.pugx.org/onmotion/yii2-page-assessments/license)](https://packagist.org/packages/onmotion/yii2-page-assessments)

*Всплывающий:*

![fluent](https://github.com/onmotion/yii2-page-assessments/blob/docs/docs/fluent.gif?raw=true)

*Статичный (встроенный в страницу):*

![fluent](https://github.com/onmotion/yii2-page-assessments/blob/docs/docs/static.gif?raw=true)

Installation
--

Выполните:

    composer require onmotion/yii2-page-assessments

или добавьте 

    "onmotion/yii2-page-assessments": "*"

в ваш composer.json файл.

Примените миграцию:

    php yii migrate --migrationPath=@vendor/onmotion/yii2-page-assessments/migrations

Использование
--

Необходимо добавить в конфиг приложения:

```php
'modules' => [
    //...
    'assessments' => [
        'class' => 'onmotion\assessments\Module',
    ],
    //...
],
```

Теперь можно использовать виджет на странице:

*Пример:*

```php
echo \onmotion\assessments\widget\AssessmentWidget::widget([
    'fluent' => false, // static or fluent view
    'questions' => [
        'Is this page helpful?', // simple question
        [
            'title' => 'What do you think about it?',
            'maxValue' => 6, 
          //  'allowComment' => true // allow optional comment
            'allowComment' => [1, 2, 3], // allow comment only if value is 1, 2 or 3.
          //  'model' => (new SomeActiveRecordModel),
        ]
    ]
]);
```
Опции
--

| option     | type | default  | description |
| --------   | --------  | --------  | --------  |
| **fluent**     | bool     | false    | fluent or static view  |
| **questions**   | string \|\| array   | see example | 
| **model** _(optional)_   | ActiveRecord   | null | Attach `Model::class` info with primary key  |


События
--

**assessment.show** - когда вопрос отобразился.

```javascript
document.addEventListener('assessment.show', function (e) {
    console.log(e.detail);
}, false);
```
