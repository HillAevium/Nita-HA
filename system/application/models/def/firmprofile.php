<?php

require_once APPPATH.'/libraries/core/auth/session/user_credentials.php';

class FirmProfileDefinition extends Model_Definition {
    
    private static $userTypes = array (
        'group' => USER_SUPER,
        'individual' => USER_NORMAL
    );
    
    public function FirmProfileDefinition() {
        parent::Model_Definition();
        $this->initFields();
    }
    
    private function initFields() {
        $userTypeField = new Enum_Field('userType', self::$userTypes);
        
        $this->startRequiredBlock();
        $this->addField($userTypeField);
        
        $this->startDependantBlock($userTypeField, 'group');
        $this->addField(new String_Field('name'));
        $this->addField(new String_Field('billingAddress1'));
        $this->addField(new String_Field('billingCity'));
        $this->addField(new String_Field('billingState'));
        $this->addField(new String_Field('billingZip'));
        $this->addField(new String_Field('billingCountry'));
        $this->addField(new String_Field('shippingAddress1'));
        $this->addField(new String_Field('shippingCity'));
        $this->addField(new String_Field('shippingState'));
        $this->addField(new String_Field('shippingZip'));
        $this->addField(new String_Field('shippingCountry'));
        $this->addField(new String_Field('phone1'));
        $this->addField(new String_Field('fax'));
        $this->addField(new String_Field('firmSize'));
        
        $this->startOptionalBlock();
        $this->addField(new String_Field('practiceType'));
        $this->addField(new String_Field('trainingDirector'));
        $this->addField(new String_Field('billingAddress2'));
        $this->addField(new String_Field('shippingAddress2'));
    }
}