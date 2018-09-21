# yii2-page-assessments
The page assessment module for Yii2 framework

*Fluent view:*

![fluent](https://github.com/onmotion/yii2-page-assessments/blob/docs/docs/fluent.gif?raw=true)

*Static (embeded in a page) view:*

![fluent](https://github.com/onmotion/yii2-page-assessments/blob/docs/docs/static.gif?raw=true)

Installation
--

Just run:

composer require onmotion/yii2-page-assessments

or add 

"onmotion/yii2-page-assessments": "*"

to the require section of your composer.json file.

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

*Fluent view example:*

```php
echo \onmotion\assessments\widget\AssessmentWidget::widget([
'fluent' => false, // static or fluent view
'questions' => [
'Is this page helpful?', // simple question
[
'title' => 'What do you think about it?',
'maxValue' => 6, 
'allowComment' => true // allow optional comment
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
| **model** _(optional)_   | ActiveRecord   | null | Attach `Model::class` info with primary key  |
