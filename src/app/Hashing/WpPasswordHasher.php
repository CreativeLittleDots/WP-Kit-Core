<?php

namespace WPKit\Hashing;

use RuntimeException;
use PasswordHash;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class WpPasswordHasher implements HasherContract
{
	
	/**
     * Default hasher
     *
     * @var PasswordHash
     */
	protected $hasher;
	
    /**
     * Default crypt cost factor.
     *
     * @var int
     */
    protected $rounds = 10;
    
    public function __construct(PasswordHash $hasher) {
	    
	    $this->hasher = $hasher;
	    
    }

    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     *
     * @throws \RuntimeException
     */
    public function make($value, array $options = [])
    {
        $hash = $this->hasher->HashPassword($value);

        if ($hash === false) {
            throw new RuntimeException('WpPasswordHasher hashing not supported.');
        }

        return $hash;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return $this->hasher->CheckPassword($value, $hashedValue);
    }
    
    
    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
    
}
