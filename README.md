Vegas CMF Forms lib
======================

# List of contents

* Vegas\Forms\Element\Cloneable
* Vegas\Forms\Element\Datepicker
* Vegas\Forms\Element\MultiSelect

# Vegas\Forms\Element\Cloneable #

Element with add/remove field buttons. You can use single field as base element (generated field name: *cloneableElementName[]*) or array of fields (generated fields names: *cloneableElementName[0..n][baseFieldName]*).

### Usage ###

```
#!php
<?php
// always set the base element!
$answers = new Cloneable('answers');
$answers->setBaseElements(array(
    new Text('field1'),
    new Text('field2')
);
// and/or
$answers->addBaseElement(new Text(''));
$answers->setLabel($this->i18n->_('Answers'));
$answers->addValidator(new SizeOf(array('min' => 2, 'max' => 6)));
$this->add($answers);
```

### Also check ###

* SizeOf Validator from vegas-cmf/validation library


# Vegas\Forms\Element\Datepicker #

Simple input with [bootstrap-datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/).


# Vegas\Forms\Element\MultiSelect #

Extension for \Phalcon\Forms\Element\Select that accept and save values as array.

### Usage ###
```
#!php
<?php
$multi = new MultiSelect('test', array(
    'one' => 'One',
    'two' => 'Two',
    'three' => '3'
));
$this->add($multi);
```

# Vegas\Forms\Element\Upload #

Input still to come.


# Vegas\Forms\Element\RichTextArea #

TextArea extension with ckEditor.