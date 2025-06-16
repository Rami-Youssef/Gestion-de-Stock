<?php

namespace App\Models;

/**
 * User class - Alias for Utilisateur to maintain backward compatibility
 * 
 * This class extends Utilisateur to prevent breaking changes in existing code
 * that may reference the User model.
 */
class User extends Utilisateur
{
    // This class inherits everything from Utilisateur
}
