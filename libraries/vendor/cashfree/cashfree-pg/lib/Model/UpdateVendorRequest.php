<?php
/**
 * UpdateVendorRequest
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  Cashfree
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Cashfree Payment Gateway APIs
 *
 * Cashfree's Payment Gateway APIs provide developers with a streamlined pathway to integrate advanced payment processing capabilities into their applications, platforms and websites.
 *
 * The version of the OpenAPI document: 2023-08-01
 * Contact: developers@cashfree.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 7.0.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Cashfree\Model;

use \ArrayAccess;
use \Cashfree\ObjectSerializer;

/**
 * UpdateVendorRequest Class Doc Comment
 *
 * @category Class
 * @description Update Vendor Request
 * @package  Cashfree
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class UpdateVendorRequest implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'UpdateVendorRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'status' => 'string',
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'verify_account' => 'bool',
        'dashboard_access' => 'bool',
        'schedule_option' => 'float',
        'bank' => '\Cashfree\Model\BankDetails[]',
        'upi' => '\Cashfree\Model\UpiDetails[]',
        'kyc_details' => '\Cashfree\Model\KycDetails[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'status' => null,
        'name' => null,
        'email' => null,
        'phone' => null,
        'verify_account' => null,
        'dashboard_access' => null,
        'schedule_option' => null,
        'bank' => null,
        'upi' => null,
        'kyc_details' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static $openAPINullables = [
        'status' => false,
		'name' => false,
		'email' => false,
		'phone' => false,
		'verify_account' => false,
		'dashboard_access' => false,
		'schedule_option' => false,
		'bank' => false,
		'upi' => false,
		'kyc_details' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'status' => 'status',
        'name' => 'name',
        'email' => 'email',
        'phone' => 'phone',
        'verify_account' => 'verify_account',
        'dashboard_access' => 'dashboard_access',
        'schedule_option' => 'schedule_option',
        'bank' => 'bank',
        'upi' => 'upi',
        'kyc_details' => 'kyc_details'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'status' => 'setStatus',
        'name' => 'setName',
        'email' => 'setEmail',
        'phone' => 'setPhone',
        'verify_account' => 'setVerifyAccount',
        'dashboard_access' => 'setDashboardAccess',
        'schedule_option' => 'setScheduleOption',
        'bank' => 'setBank',
        'upi' => 'setUpi',
        'kyc_details' => 'setKycDetails'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'status' => 'getStatus',
        'name' => 'getName',
        'email' => 'getEmail',
        'phone' => 'getPhone',
        'verify_account' => 'getVerifyAccount',
        'dashboard_access' => 'getDashboardAccess',
        'schedule_option' => 'getScheduleOption',
        'bank' => 'getBank',
        'upi' => 'getUpi',
        'kyc_details' => 'getKycDetails'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('name', $data ?? [], null);
        $this->setIfExists('email', $data ?? [], null);
        $this->setIfExists('phone', $data ?? [], null);
        $this->setIfExists('verify_account', $data ?? [], null);
        $this->setIfExists('dashboard_access', $data ?? [], null);
        $this->setIfExists('schedule_option', $data ?? [], null);
        $this->setIfExists('bank', $data ?? [], null);
        $this->setIfExists('upi', $data ?? [], null);
        $this->setIfExists('kyc_details', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['status'] === null) {
            $invalidProperties[] = "'status' can't be null";
        }
        if ($this->container['name'] === null) {
            $invalidProperties[] = "'name' can't be null";
        }
        if ($this->container['email'] === null) {
            $invalidProperties[] = "'email' can't be null";
        }
        if ($this->container['phone'] === null) {
            $invalidProperties[] = "'phone' can't be null";
        }
        if ($this->container['schedule_option'] === null) {
            $invalidProperties[] = "'schedule_option' can't be null";
        }
        if ($this->container['kyc_details'] === null) {
            $invalidProperties[] = "'kyc_details' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string $status Specify the status of vendor that should be updated. Possible values: ACTIVE,BLOCKED, DELETED
     *
     * @return self
     */
    public function setStatus($status)
    {
        if (is_null($status)) {
            throw new \InvalidArgumentException('non-nullable status cannot be null');
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets name
     *
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     *
     * @param string $name Specify the name of the vendor to be updated. Name should not have any special character except . / - &
     *
     * @return self
     */
    public function setName($name)
    {
        if (is_null($name)) {
            throw new \InvalidArgumentException('non-nullable name cannot be null');
        }
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->container['email'];
    }

    /**
     * Sets email
     *
     * @param string $email Specify the vendor email ID that should be updated. String in email ID format (Ex:johndoe_1@cashfree.com) should contain @ and dot (.)
     *
     * @return self
     */
    public function setEmail($email)
    {
        if (is_null($email)) {
            throw new \InvalidArgumentException('non-nullable email cannot be null');
        }
        $this->container['email'] = $email;

        return $this;
    }

    /**
     * Gets phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->container['phone'];
    }

    /**
     * Sets phone
     *
     * @param string $phone Specify the beneficiaries phone number to be updated. Phone number registered in India (only digits, 8 - 12 characters after excluding +91).
     *
     * @return self
     */
    public function setPhone($phone)
    {
        if (is_null($phone)) {
            throw new \InvalidArgumentException('non-nullable phone cannot be null');
        }
        $this->container['phone'] = $phone;

        return $this;
    }

    /**
     * Gets verify_account
     *
     * @return bool|null
     */
    public function getVerifyAccount()
    {
        return $this->container['verify_account'];
    }

    /**
     * Sets verify_account
     *
     * @param bool|null $verify_account Specify if the vendor bank account details should be verified. Possible values: true or false
     *
     * @return self
     */
    public function setVerifyAccount($verify_account)
    {
        if (is_null($verify_account)) {
            throw new \InvalidArgumentException('non-nullable verify_account cannot be null');
        }
        $this->container['verify_account'] = $verify_account;

        return $this;
    }

    /**
     * Gets dashboard_access
     *
     * @return bool|null
     */
    public function getDashboardAccess()
    {
        return $this->container['dashboard_access'];
    }

    /**
     * Sets dashboard_access
     *
     * @param bool|null $dashboard_access Update if the vendor will have dashboard access or not. Possible values are: true or false
     *
     * @return self
     */
    public function setDashboardAccess($dashboard_access)
    {
        if (is_null($dashboard_access)) {
            throw new \InvalidArgumentException('non-nullable dashboard_access cannot be null');
        }
        $this->container['dashboard_access'] = $dashboard_access;

        return $this;
    }

    /**
     * Gets schedule_option
     *
     * @return float
     */
    public function getScheduleOption()
    {
        return $this->container['schedule_option'];
    }

    /**
     * Sets schedule_option
     *
     * @param float $schedule_option Specify the settlement cycle to be updated. View the settlement cycle details from the \"Settlement Cycles Supported\" table. If no schedule option is configured, the settlement cycle ID \"1\" will be in effect. Select \"8\" or \"9\" if you want to schedule instant vendor settlements.
     *
     * @return self
     */
    public function setScheduleOption($schedule_option)
    {
        if (is_null($schedule_option)) {
            throw new \InvalidArgumentException('non-nullable schedule_option cannot be null');
        }
        $this->container['schedule_option'] = $schedule_option;

        return $this;
    }

    /**
     * Gets bank
     *
     * @return \Cashfree\Model\BankDetails[]|null
     */
    public function getBank()
    {
        return $this->container['bank'];
    }

    /**
     * Sets bank
     *
     * @param \Cashfree\Model\BankDetails[]|null $bank Specify the vendor bank account details to be updated.
     *
     * @return self
     */
    public function setBank($bank)
    {
        if (is_null($bank)) {
            throw new \InvalidArgumentException('non-nullable bank cannot be null');
        }
        $this->container['bank'] = $bank;

        return $this;
    }

    /**
     * Gets upi
     *
     * @return \Cashfree\Model\UpiDetails[]|null
     */
    public function getUpi()
    {
        return $this->container['upi'];
    }

    /**
     * Sets upi
     *
     * @param \Cashfree\Model\UpiDetails[]|null $upi Updated beneficiary upi vpa. Alphanumeric, dot (.), hyphen (-), at sign (@), and underscore allowed (100 character limit). Note: underscore and dot (.) gets accepted before and after @, but hyphen (-) is only accepted before @ sign.
     *
     * @return self
     */
    public function setUpi($upi)
    {
        if (is_null($upi)) {
            throw new \InvalidArgumentException('non-nullable upi cannot be null');
        }
        $this->container['upi'] = $upi;

        return $this;
    }

    /**
     * Gets kyc_details
     *
     * @return \Cashfree\Model\KycDetails[]
     */
    public function getKycDetails()
    {
        return $this->container['kyc_details'];
    }

    /**
     * Sets kyc_details
     *
     * @param \Cashfree\Model\KycDetails[] $kyc_details Specify the kyc details that should be updated.
     *
     * @return self
     */
    public function setKycDetails($kyc_details)
    {
        if (is_null($kyc_details)) {
            throw new \InvalidArgumentException('non-nullable kyc_details cannot be null');
        }
        $this->container['kyc_details'] = $kyc_details;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


