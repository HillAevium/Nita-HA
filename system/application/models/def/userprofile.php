<?php

require_once APPPATH.'/models/core/model_definition.php';
require_once APPPATH.'/models/core/array_field.php';
require_once APPPATH.'/models/core/boolean_field.php';
require_once APPPATH.'/models/core/date_field.php';
require_once APPPATH.'/models/core/email_field.php';
require_once APPPATH.'/models/core/enum_field.php';
require_once APPPATH.'/models/core/int_field.php';
require_once APPPATH.'/models/core/password_field.php';
require_once APPPATH.'/models/core/string_field.php';

class UserProfileDefinition extends Model_Definition {
    
    public function UserProfileDefinition() {
        $this->initFields();
    }
    
    private function initFields() {
        // Always Required
        $this->startRequiredBlock();
        $this->addField(new String_Field(  'firstName'));
        $this->addField(new String_Field(  'lastName'));
        $this->addField(new Email_Field(   'email'));
        $this->addField(new Password_Field('password', '', 6, 20));
        $this->addField(new String_Field(  'phone'));
        $this->addField(new String_Field(  'role'));
        $attendingField = new Boolean_Field('isAttendingClasses');
        $this->addField($attendingField);
        
        // Optional unless the user is attending classes
        $this->startDependantBlock($attendingField, '1');
        $this->addField(new Array_Field(new String_Field('barId'), 0, 50));
        $this->addField(new Array_Field(new String_Field('state'), 0, 50));
        $this->addField(new Array_Field(new Date_Field('date'), 0, 50));
        $this->addField(new String_Field(  'badgeName'));
        $this->addField(new String_Field(  'billingAddress1'));
        $this->addField(new String_Field(  'billingCity'));
        $this->addField(new String_Field(  'billingState'));
        $this->addField(new String_Field(  'billingZip'));
        $this->addField(new String_Field(  'billingCountry'));
        $this->addField(new String_Field(  'shippingAddress1'));
        $this->addField(new String_Field(  'shippingCity'));
        $this->addField(new String_Field(  'shippingState'));
        $this->addField(new String_Field(  'shippingZip'));
        $this->addField(new String_Field(  'shippingCountry'));
        $this->addField(new Boolean_Field( 'requireAccessibility'));
        $this->addField(new Boolean_Field( 'haveScholarship'));
        
        // Optional
        $this->startOptionalBlock();
        $this->addField(new Int_Field('accountId'));
        $this->addField(new String_Field('salutation'));
        $this->addField(new String_Field('middleInitial'));
        $this->addField(new String_Field('suffix'));
        $this->addField(new String_Field('title'));
        $this->addField(new String_Field('phone2'));
        $this->addField(new String_Field('fax'));
        $this->addField(new String_Field('companyName'));
        $this->addField(new String_Field('typeOfPractice'));
        $this->addField(new String_Field('lawSchoolAttended'));
        $this->addField(new String_Field('firmSize'));
        $this->addField(new String_Field('ethnicity'));
        $this->addField(new String_Field('lawInterests'));
        $this->addField(new String_Field('trainingDirector'));
        $this->addField(new String_Field('billingAddress2'));
        $this->addField(new String_Field('shippingAddress2'));
    }
}