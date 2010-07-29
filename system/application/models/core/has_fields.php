<?php

interface Has_Fields {
    
    /**
     * Adds a new field to the collection.
     *
     * By default this method will allow duplicates. It does
     * not work like a 'set'. If 'set' behavior is needed then
     * the caller should pre-filter the list.
     *
     * @param Field $field the field to add
     */
    public function addField(Field $field);
    
    /**
     * Retrieve the collection of fields that have been added.
     *
     * If no fields are present then an empty list will be returned.
     *
     * @return array(Field) the list of fields
     */
    public function fields();
    
    public function names();
}