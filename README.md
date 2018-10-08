# Yii2 page assessment/polls extension
The page assessment (polls) module for Yii2 framework based on Vue2.

Language: [English](README.md), [Русский](README.ru.md)

[![Latest Stable Version](https://poser.pugx.org/onmotion/yii2-page-assessments/v/stable)](https://packagist.org/packages/onmotion/yii2-page-assessments)
[![Total Downloads](https://poser.pugx.org/onmotion/yii2-page-assessments/downloads)](https://packagist.org/packages/onmotion/yii2-page-assessments)
[![Monthly Downloads](https://poser.pugx.org/onmotion/yii2-page-assessments/d/monthly)](https://packagist.org/packages/onmotion/yii2-page-assessments)
[![License](https://poser.pugx.org/onmotion/yii2-page-assessments/license)](https://packagist.org/packages/onmotion/yii2-page-assessments)

*Fluent view:*

![fluent](https://github.com/onmotion/yii2-page-assessments/blob/docs/docs/fluent.gif?raw=true)

*Static (embeded in a page) view:*

![static](https://github.com/onmotion/yii2-page-assessments/blob/docs/docs/static.gif?raw=true)

Installation
--

Just run:

    composer require onmotion/yii2-page-assessments

or add 

    "onmotion/yii2-page-assessments": "*"

to the require section of your composer.json file.

apply migration:

    php yii migrate --migrationPath=@vendor/onmotion/yii2-page-assessments/migrations

Usage
--

You must add to your config:

```php
'modules' => [
    //...
    'assessments' => [
        'class' => 'onmotion\assessments\Module',
    ],
    //...
],
```

Then you can use the widget somewhere on the page:

*Example:*

```php
echo \onmotion\assessments\widget\AssessmentWidget::widget([
    'fluent' => false, // static or fluent view
   // 'timeout' => 1500 // timeout bafore appearance.
    'questions' => [
        'Is this page helpful?', // simple question
        [
            'title' => 'What do you think about it?',
            'maxValue' => 6, 
          //  'allowComment' => true // allow optional comment
            'allowComment' => [1, 2, 3], // allow comment only if value is 1, 2 or 3.
          //  'model' => (new SomeActiveRecordModel),
          //  'repeat' => true, // whether to force repeat the question
          //  'afterVoteText' => [  // show comment after got a vote (string || array)
          //                    1 => 'It's ok! 👌',
          //                    2 => 'Could be better 😎',
          //                    3 => 'So so...',
          //                    4 => 'Very good!',
          //                    5 => 'Perfect!',
          //                ]
        ]
    ]
]);
```
Options
--

| option     | type | default  | description |
| --------   | --------  | --------  | --------  |
| **fluent**     | bool     | false    | fluent or static view  |
| **questions**   | string \|\| array   | see example | 
| **timeout** | integer | 1500 | timeout bafore appearance |
| **model** _(optional)_   | ActiveRecord   | null | Attach `Model::class` info with primary key  |
| **icons** | array | [] | custom icons |

Events
--

**assessment.show** - when an assessment item appears.

**assessment.end** - when all questions are completed.

```javascript
document.addEventListener('assessment.show', function (e) {
    console.log(e.detail);
}, false);
```

---

You can change icons as you want, for example:

```php
echo \onmotion\assessments\widget\AssessmentWidget::widget([
            'fluent' => true,
            'questions' => $questions,
            'icons' => [
                    1 => '<span class="assessment-icon"><span class="assessment-icon__angry"></span></span>',
                    2 => '<span class="assessment-icon"><span class="assessment-icon__sad"></span></span>',
                    3 => '<span class="assessment-icon"><span class="assessment-icon__thinking"></span></span>',
                    4 => '<span class="assessment-icon"><span class="assessment-icon__happy"></span></span>',
                    5 => '<span class="assessment-icon"><span class="assessment-icon__in-love"></span></span>',
            ]
        ]);
```

![icons](https://github.com/onmotion/yii2-page-assessments/blob/docs/docs/icons.png?raw=true)
