<?php

/**
 * A container for handling filters to be used by the Soap API.
 *
 * @author Chris Boertien (chris.boertien@gmail.com)
 */
class FilterSet {
    
    private $filters = array(); // Filter
    
    public function FilterSet() {
        
    }
    
    /**
     * Add a new Filter to this FilterSet
     *
     * @param Filter $filter a search filter
     */
    public function addFilter(Filter $filter) {
        $this->filters[] = $filter;
    }
    
    /**
     * Returns an array of [SoapVar?] objects for the
     * SoapClient to use for processing searches.
     */
    public function getFilters() {
        $soapVars = array();
        
        // Call each of the filters in order and stack
        // them onto the array for output
        foreach($filters as $filter) {
            $soapVar = $filter->getFilter();
            
            // If the filter handed us an array of vars,
            // which may be the case for some filter types,
            // then we push the elements onto our existing
            // array. Otherwise add it directly.
            if(is_array($soapVar)) {
                array_merge($soapVars, $soapVar);
            } else {
                $soapVars[] = $soapVar;
            }
        }
        
        return $soapVars;
    }
}

/* End of file filterset.php */