<?php

require_once APPPATH.'/models/def/definition.php';

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
    
    public function UserProfileDefinition() {
        $this->initFields();
    }
    
    private function initFields() {
        /* Required Fields */
        /*                         Name             Type        Required  */
        $this->addField(new Field('firstName',          'string',   true));
        $this->addField(new Field('lastName',           'string',   true));
        $this->addField(new Field('email',              'string',   true));
        $this->addField(new Field('password',           'string',   true));
        $this->addField(new Field('phone',              'string',   true));
        $this->addField(new Field('role',               'string',   true));
        $this->addField(new Field('billingAddress1',    'string',   true));
        $this->addField(new Field('billingAddress2',    'string',   true));
        $this->addField(new Field('billingCity',        'string',   true));
        $this->addField(new Field('billingState',       'state',    true));
        $this->addField(new Field('billingZip',         'string',   true));
        $this->addField(new Field('billingCountry',     'string',   true));
        $this->addField(new Field('shippingAddress1',   'string',   true));
        $this->addField(new Field('shippingAddress2',   'string',   true));
        $this->addField(new Field('shipgingCity',       'string',   true));
        $this->addField(new Field('shippingState',      'string',   true));
        $this->addField(new Field('shippingZip',        'string',   true));
        $this->addField(new Field('shippingCountry',    'string',   true));
        $this->addField(new Field('requireAccessibility','boolean', true));
        $this->addField(new Field('haveScolarship',     'boolean',  true));
        
        /* Optional Fields */
        /*                         Name             Type        Required  */
        $this->addField(new Field('accountId',      'int',      false));
        $this->addField(new Field('salutation',     'string',   false));
        $this->addField(new Field('middleInitial',  'string',   false));
        $this->addField(new Field('suffix',         'string',   false));
        $this->addField(new Field('title',          'string',   false));
        $this->addField(new Field('phone2',         'string',   false));
        $this->addField(new Field('fax',            'string',   false));
        $this->addField(new Field('companyName',    'string',   false));
        $this->addField(new Field('typeOfPractice', 'string',   false));
        $this->addField(new Field('lawSchoolAttended','string', false));
        $this->addField(new Field('firmSize',       'int',      false));
        $this->addField(new Field('ethnicity',      'string',   false));
        $this->addField(new Field('lawInterests',   'string',   false));
        $this->addField(new Field('trainingDirector','string',  false));
    }
}