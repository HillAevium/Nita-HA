<?php

/**
 * A Filter is used by the Soap API to apply filtering
 * stratagies for program and publication queries.
 *
 * A Filter defines its own private set of constraints
 * that it encapsulates. Filters should define a single
 * type of Filter, and may define multiple parameters
 * based on that type of Filter.
 *
 * For example, a PriceFilter may have upper bounds or
 * lower bounds, or may also use both.
 * NOTE: Need to research what the Soap API is going to
 *       require for getFilter(). Should be a SoapVar
 *       which will get included in the SoapClient call.
 *
 * @author Owner
 *
 */
interface Filter {
    
    /**
     * Returns a [SoapVar?] for the SoapClient to use in
     * processing search requests.
     */
    function getFilter();
}

/* End of file Filter.php */