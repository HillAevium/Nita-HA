<?php

define('AUTH_NOT_AUTHENTICATED', 0);
define('AUTH_AUTHENTICATED', 1);

require_once APPPATH.'/libraries/core/auth/credentials.php';

interface Authenticator {
    
    /**
     * Initialize the authenticator.
     */
    public function init();
    
    /**
     * Grants authentication to an entity.
     *
     * The id of the entity can later be recalled by
     * retrieving the credentials for the entity.
     *
     * @param string $id
     */
    public function grant($id, array $user);
    
    /**
     * Revokes authentication for an entity.
     */
    public function revoke();
    
    /**
     * Tests whether the entity has been granted authentication.
     *
     * @return true if the entity is authenticated
     */
    public function isAuthenticated();
    
    /**
     * Retrieves the credentials of an entity.
     *
     * If the entity has not be granted authentication
     * privileges then no credentials are returned.
     *
     * @return the credentials for the entity, or null
     */
    public function credentials();
}