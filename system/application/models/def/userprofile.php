<?php

require_once APPPATH.'/models/def/definition.php';
require_once APPPATH.'/models/def/hasdefinition.php';

class UserProfile implements HasDefinition {
    
    private static $definition;
    
    public function UserProfile() {
        self::$definition = new UserProfileDefinition();
    }
    
    public function getDefinition() {
        return self::$definition;
    }
}

class UserProfileDefinition extends Definition {
    
    public function UserProfileDefinition($isFull) {
        $this->initFields($isFull);
    }
    
    private function initFields($isFull) {
        $barDef = new ArrayField('bar', $isFull ? 1 : 0, 50);
        
        /* Required Fields */
        /*                         Name             Type        Required  */
        $barDef->addField(new Field('barId',            'bar',    true));
        $barDef->addField(new Field('state',            'state',    true));
        $barDef->addField(new Field('date',             'date',    true));
        $this->addField($barDef);
        
        $this->addField(new Field('firstName',          'string',   true));
        $this->addField(new Field('lastName',           'string',   true));
        $this->addField(new Field('email',              'email',    true));
        $this->addField(new Field('password',           'password', true));
        $this->addField(new Field('phone',              'phone',    true));
        $this->addField(new Field('role',               'string',   $isFull));
        $this->addField(new Field('isAttendingClasses', 'boolean',  true));
        $this->addField(new Field('badgeName',          'string',   $isFull));
        $this->addField(new Field('billingAddress1',    'string',   true));
        $this->addField(new Field('billingAddress2',    'string',   true));
        $this->addField(new Field('billingCity',        'string',   true));
        $this->addField(new Field('billingState',       'state',    true));
        $this->addField(new Field('billingZip',         'string',   true));
        $this->addField(new Field('billingCountry',     'string',   true));
        $this->addField(new Field('shippingAddress1',   'string',   true));
        $this->addField(new Field('shippingAddress2',   'string',   true));
        $this->addField(new Field('shippingCity',       'string',   true));
        $this->addField(new Field('shippingState',      'string',   true));
        $this->addField(new Field('shippingZip',        'string',   true));
        $this->addField(new Field('shippingCountry',    'string',   true));
        //$this->addField(new Field('requireAccessibility','boolean', true));
        //$this->addField(new Field('haveScolarship',     'boolean',  true));
        
        /* Optional Fields */
        /*                         Name             Type        Required  */
        $this->addField(new Field('accountId',      'int',      false));
        $this->addField(new Field('salutation',     'string',   false));
        $this->addField(new Field('middleInitial',  'string',   false));
        $this->addField(new Field('suffix',         'string',   false));
        $this->addField(new Field('title',          'string',   false));
        $this->addField(new Field('phone2',         'phone',    false));
        $this->addField(new Field('fax',            'phone',    false));
        $this->addField(new Field('companyName',    'string',   false));
        $this->addField(new Field('typeOfPractice', 'string',   false));
        $this->addField(new Field('lawSchoolAttended','string', false));
        $this->addField(new Field('firmSize',       'string',   false));
        $this->addField(new Field('ethnicity',      'string',   false));
        $this->addField(new Field('lawInterests',   'string',   false));
        $this->addField(new Field('trainingDirector','string',  false));
    }
}