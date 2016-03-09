<?php
/**
 * Co-Registration API helper
 *
 * @package DatingVIP
 * @subpackage api
 * @category lib
 * @author Boris Momčilović <boris@firstbeatmedia.com>
 * @copyright All rights reserved
 * @version 1.0
 */

namespace DatingVIP\API;

use DatingVIP\API\Client;
use DatingVIP\API\Command;
use DatingVIP\API\Response;

class CoReg
{
    const CMD_REGISTER          = 'register.api';
    const CMD_ZIP_AND_CITY      = 'register.zip-city-lookup';
    const CMD_CHECK_USERNAME    = 'register.check-username';
    const CMD_CHECK_EMAIL       = 'utils.email';
    const CMD_GEO_FORMATS       = 'utils.country-geo-formats';
    const CMD_GET_GENDERS       = 'utils.website-genders';

/**
 * Instance of DatingVIP\API\Client
 *
 * @var Client
 * @access private
 */
    private $api;

/**
 * Default CoReg API constructor
 *
 * @param Client $api
 * @access public
 */
    public function __construct(Client $api)
    {
        $this->api = $api;
    }

/**
 * Register member
 *
 * Mandatory params:
 * - email:         valid email address
 * - ip_address:    requester's IP address
 *
 * Optional params:
 * - username       (string) 6-20 chars [a-z\p{L}0-9_\.\-])
 *   if not provided (or already existing supplied) new one will be created
 *
 * - birthdate      (string)
 *   eg: '1983-02-10'
 *
 * - age            (integer)
 *   eg: 32, if not passed, will be calculated from birthdate if provided
 *
 * - gender         (integer)
 *   eg: 1, member's gender (genders identificators depend on the target site and/or system)
 *
 * - looking        (integer)
 *   eg: 2          member's "I'm looking for 'gender'" (genders identificators depend on the target site and/or system)
 *
 * - title          (string) 255 chars
 *   eg: "I'm awesome"
 *
 * - password       (string)
 *   If not provided, password will be generated and returned
 *
 * - password_re    (string)
 *   If provided, must match password
 *
 * - firstname      (string) 20 chars
 *   eg: "Geoffrey" - members first name
 *
 * Additional params related to geo location (country, city and/or zip) must match DatingVIP patterns.
 * (See helper methods below on geo data)
 * - country		(string) 2 chars, ISO 3166-1 alpha-2
 *   eg: "BR"
 *
 * - city			(string)
 *   eg: "Sao Paolo"
 *
 * - zip			(string)
 *   eg: "24108"
 *
 * Affiliate data is optional, but if it's supplied then following params are
 * considered, and following rules apply:
 *
 * Mandatory params:
 * - aff_id
 * - aff_pg
 * - _unique
 *   You need to check if click is unique and send 1 if it is, or 0 if it is not
 *
 * - _domain
 *   Domain name you're targeting, eg: www.domain.com - domain name only, don't include protocol
 *
 * There's no mistake - _unique and _domain come with underscores.
 *
 * Optional params:
 * - aff_cp
 * - aff_tr
 * - aff_kw
 * - aff_src
 * - aff_adg
 * - HTTP_REFERER
 *
 * @param array $data
 * @access public
 * @return Response
 */
    public function register(array $data)
    {
        $command = new Command (self::CMD_REGISTER, array_filter ($data));
        return $this->api->execute ($command);
    }

/**
 * Get possible zip and city values for given country and term
 * Term should be at least three chars
 *
 * @param string $country
 * @param string $term
 * @access public
 * @return Response
 */
    public function zipAndCity($country, $term)
    {
        $command = new Command (self::CMD_ZIP_AND_CITY, [
            'country'	=> $this->sanatizeCountry ($country),
            'term'		=> $this->sanatizeString ($term)
        ]);
        return $this->api->execute ($command);
    }

/**
 * Check if given username already has an account with given website
 *
 * @param string $username
 * @access public
 * @return Response
 */
    public function checkUsername($username)
    {
        $command = new Command (self::CMD_CHECK_USERNAME, ['username' => $this->sanatizeString ($username)]);
        return $this->api->execute ($command);
    }

/**
 * Check if given email already has an account with given website
 *
 * @param string $email
 * @access public
 * @return Response
 */
    public function checkEmail($email)
    {
        $command = new Command (self::CMD_CHECK_EMAIL, ['email' => $this->sanatizeString ($email)]);
        return $this->api->execute ($command);
    }

/**
 * Get information on required geo data for given country
 *
 * @param string $country
 * @access public
 * @return Response
 */
    public function getGeoFormats($country)
    {
        $command = new Command (self::CMD_GEO_FORMATS, ['country' => $this->sanatizeCountry ($country)]);
        return $this->api->execute ($command);
    }

/**
 * Execute API command to fetch available website genders
 *
 * @param void
 * @access public
 * @return Response
 */
    public function getWebsiteGenders()
    {
        $command = new Command (self::CMD_GET_GENDERS);
        return $this->api->execute ($command);
    }

/**
 * Sanatize country input
 *
 * @param string $country
 * @access private
 * @return string
 */
    private function sanatizeCountry($country)
    {
        return is_scalar ($country) ? strtoupper (substr ($country, 0, 2)) : '';
    }

/**
 * Sanatize input string
 *
 * @param string $input
 * @access private
 * @return string
 */
    private function sanatizeString($input)
    {
        return is_scalar ($input) ? (string) $input : '';
    }

}
