<?php

class FirmProfileDefinition extends Model_Definition {
    
    public function FirmProfileDefinition() {
        parent::Model_Definition();
        $this->initFields();
    }
    
    private function initFields() {
        $this->addField(new Field('name',               'string',   true));
        $this->addField(new Field('billingAddress1',    'string',   true));
        $this->addField(new Field('billingCity',        'string',   true));
        $this->addField(new Field('billingState',       'string',   true));
        $this->addField(new Field('billingZip',         'string',   true));
        $this->addField(new Field('billingCountry',     'string',   true));
        $this->addField(new Field('shippingAddress1',   'string',   true));
        $this->addField(new Field('shippingCity',       'string',   true));
        $this->addField(new Field('shippingState',      'string',   true));
        $this->addField(new Field('shippingZip',        'string',   true));
        $this->addField(new Field('shippingCountry',    'string',   true));
        $this->addField(new Field('primaryPhone',       'string',   true));
        $this->addField(new Field('primaryFax',         'string',   true));
        $this->addField(new Field('practiceType',       'string',   true));
        $this->addField(new Field('firmSize',           'int',      true));
        $this->addField(new Field('trainingDirector',   'string',   true));
        
        $this->addField(new Field('billingAddress2',    'string',   false));
        $this->addField(new Field('shippingAddress2',   'string',   false));
    }
}