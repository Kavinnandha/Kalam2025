<?php
/**
 * DisputesEntityMerchantAccepted
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
 * DisputesEntityMerchantAccepted Class Doc Comment
 *
 * @category Class
 * @package  Cashfree
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class DisputesEntityMerchantAccepted implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'DisputesEntityMerchantAccepted';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'dispute_id' => 'int',
        'dispute_type' => 'string',
        'reason_code' => 'string',
        'reason_description' => 'string',
        'dispute_amount' => 'float',
        'created_at' => 'string',
        'respond_by' => 'string',
        'updated_at' => 'string',
        'resolved_at' => 'string',
        'dispute_status' => 'string',
        'cf_dispute_remarks' => 'string',
        'preferred_evidence' => '\Cashfree\Model\EvidencesToContestDispute[]',
        'dispute_evidence' => '\Cashfree\Model\Evidence[]',
        'order_details' => '\Cashfree\Model\OrderDetailsInDisputesEntity',
        'customer_details' => '\Cashfree\Model\CustomerDetailsInDisputesEntity'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'dispute_id' => null,
        'dispute_type' => null,
        'reason_code' => null,
        'reason_description' => null,
        'dispute_amount' => null,
        'created_at' => null,
        'respond_by' => null,
        'updated_at' => null,
        'resolved_at' => null,
        'dispute_status' => null,
        'cf_dispute_remarks' => null,
        'preferred_evidence' => null,
        'dispute_evidence' => null,
        'order_details' => null,
        'customer_details' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static $openAPINullables = [
        'dispute_id' => false,
		'dispute_type' => false,
		'reason_code' => false,
		'reason_description' => false,
		'dispute_amount' => false,
		'created_at' => false,
		'respond_by' => false,
		'updated_at' => false,
		'resolved_at' => false,
		'dispute_status' => false,
		'cf_dispute_remarks' => false,
		'preferred_evidence' => false,
		'dispute_evidence' => false,
		'order_details' => false,
		'customer_details' => false
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
        'dispute_id' => 'dispute_id',
        'dispute_type' => 'dispute_type',
        'reason_code' => 'reason_code',
        'reason_description' => 'reason_description',
        'dispute_amount' => 'dispute_amount',
        'created_at' => 'created_at',
        'respond_by' => 'respond_by',
        'updated_at' => 'updated_at',
        'resolved_at' => 'resolved_at',
        'dispute_status' => 'dispute_status',
        'cf_dispute_remarks' => 'cf_dispute_remarks',
        'preferred_evidence' => 'preferred_evidence',
        'dispute_evidence' => 'dispute_evidence',
        'order_details' => 'order_details',
        'customer_details' => 'customer_details'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'dispute_id' => 'setDisputeId',
        'dispute_type' => 'setDisputeType',
        'reason_code' => 'setReasonCode',
        'reason_description' => 'setReasonDescription',
        'dispute_amount' => 'setDisputeAmount',
        'created_at' => 'setCreatedAt',
        'respond_by' => 'setRespondBy',
        'updated_at' => 'setUpdatedAt',
        'resolved_at' => 'setResolvedAt',
        'dispute_status' => 'setDisputeStatus',
        'cf_dispute_remarks' => 'setCfDisputeRemarks',
        'preferred_evidence' => 'setPreferredEvidence',
        'dispute_evidence' => 'setDisputeEvidence',
        'order_details' => 'setOrderDetails',
        'customer_details' => 'setCustomerDetails'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'dispute_id' => 'getDisputeId',
        'dispute_type' => 'getDisputeType',
        'reason_code' => 'getReasonCode',
        'reason_description' => 'getReasonDescription',
        'dispute_amount' => 'getDisputeAmount',
        'created_at' => 'getCreatedAt',
        'respond_by' => 'getRespondBy',
        'updated_at' => 'getUpdatedAt',
        'resolved_at' => 'getResolvedAt',
        'dispute_status' => 'getDisputeStatus',
        'cf_dispute_remarks' => 'getCfDisputeRemarks',
        'preferred_evidence' => 'getPreferredEvidence',
        'dispute_evidence' => 'getDisputeEvidence',
        'order_details' => 'getOrderDetails',
        'customer_details' => 'getCustomerDetails'
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

    public const DISPUTE_TYPE_DISPUTE = 'DISPUTE';
    public const DISPUTE_TYPE_CHARGEBACK = 'CHARGEBACK';
    public const DISPUTE_TYPE_RETRIEVAL = 'RETRIEVAL';
    public const DISPUTE_TYPE_PRE_ARBITRATION = 'PRE_ARBITRATION';
    public const DISPUTE_TYPE_ARBITRATION = 'ARBITRATION';
    public const DISPUTE_TYPE_UNKNOWN_DEFAULT_OPEN_API = 'unknown_default_open_api';
    public const DISPUTE_STATUS_DISPUTE_CREATED = 'DISPUTE_CREATED';
    public const DISPUTE_STATUS_DISPUTE_DOCS_RECEIVED = 'DISPUTE_DOCS_RECEIVED';
    public const DISPUTE_STATUS_DISPUTE_UNDER_REVIEW = 'DISPUTE_UNDER_REVIEW';
    public const DISPUTE_STATUS_DISPUTE_MERCHANT_WON = 'DISPUTE_MERCHANT_WON';
    public const DISPUTE_STATUS_DISPUTE_MERCHANT_LOST = 'DISPUTE_MERCHANT_LOST';
    public const DISPUTE_STATUS_DISPUTE_MERCHANT_ACCEPTED = 'DISPUTE_MERCHANT_ACCEPTED';
    public const DISPUTE_STATUS_DISPUTE_INSUFFICIENT_EVIDENCE = 'DISPUTE_INSUFFICIENT_EVIDENCE';
    public const DISPUTE_STATUS_CHARGEBACK_CREATED = 'CHARGEBACK_CREATED';
    public const DISPUTE_STATUS_CHARGEBACK_DOCS_RECEIVED = 'CHARGEBACK_DOCS_RECEIVED';
    public const DISPUTE_STATUS_CHARGEBACK_UNDER_REVIEW = 'CHARGEBACK_UNDER_REVIEW';
    public const DISPUTE_STATUS_CHARGEBACK_MERCHANT_WON = 'CHARGEBACK_MERCHANT_WON';
    public const DISPUTE_STATUS_CHARGEBACK_MERCHANT_LOST = 'CHARGEBACK_MERCHANT_LOST';
    public const DISPUTE_STATUS_CHARGEBACK_MERCHANT_ACCEPTED = 'CHARGEBACK_MERCHANT_ACCEPTED';
    public const DISPUTE_STATUS_CHARGEBACK_INSUFFICIENT_EVIDENCE = 'CHARGEBACK_INSUFFICIENT_EVIDENCE';
    public const DISPUTE_STATUS_RETRIEVAL_CREATED = 'RETRIEVAL_CREATED';
    public const DISPUTE_STATUS_RETRIEVAL_DOCS_RECEIVED = 'RETRIEVAL_DOCS_RECEIVED';
    public const DISPUTE_STATUS_RETRIEVAL_UNDER_REVIEW = 'RETRIEVAL_UNDER_REVIEW';
    public const DISPUTE_STATUS_RETRIEVAL_MERCHANT_WON = 'RETRIEVAL_MERCHANT_WON';
    public const DISPUTE_STATUS_RETRIEVAL_MERCHANT_LOST = 'RETRIEVAL_MERCHANT_LOST';
    public const DISPUTE_STATUS_RETRIEVAL_MERCHANT_ACCEPTED = 'RETRIEVAL_MERCHANT_ACCEPTED';
    public const DISPUTE_STATUS_RETRIEVAL_INSUFFICIENT_EVIDENCE = 'RETRIEVAL_INSUFFICIENT_EVIDENCE';
    public const DISPUTE_STATUS_PRE_ARBITRATION_CREATED = 'PRE_ARBITRATION_CREATED';
    public const DISPUTE_STATUS_PRE_ARBITRATION_DOCS_RECEIVED = 'PRE_ARBITRATION_DOCS_RECEIVED';
    public const DISPUTE_STATUS_PRE_ARBITRATION_UNDER_REVIEW = 'PRE_ARBITRATION_UNDER_REVIEW';
    public const DISPUTE_STATUS_PRE_ARBITRATION_MERCHANT_WON = 'PRE_ARBITRATION_MERCHANT_WON';
    public const DISPUTE_STATUS_PRE_ARBITRATION_MERCHANT_LOST = 'PRE_ARBITRATION_MERCHANT_LOST';
    public const DISPUTE_STATUS_PRE_ARBITRATION_MERCHANT_ACCEPTED = 'PRE_ARBITRATION_MERCHANT_ACCEPTED';
    public const DISPUTE_STATUS_PRE_ARBITRATION_INSUFFICIENT_EVIDENCE = 'PRE_ARBITRATION_INSUFFICIENT_EVIDENCE';
    public const DISPUTE_STATUS_ARBITRATION_CREATED = 'ARBITRATION_CREATED';
    public const DISPUTE_STATUS_ARBITRATION_DOCS_RECEIVED = 'ARBITRATION_DOCS_RECEIVED';
    public const DISPUTE_STATUS_ARBITRATION_UNDER_REVIEW = 'ARBITRATION_UNDER_REVIEW';
    public const DISPUTE_STATUS_ARBITRATION_MERCHANT_WON = 'ARBITRATION_MERCHANT_WON';
    public const DISPUTE_STATUS_ARBITRATION_MERCHANT_LOST = 'ARBITRATION_MERCHANT_LOST';
    public const DISPUTE_STATUS_ARBITRATION_MERCHANT_ACCEPTED = 'ARBITRATION_MERCHANT_ACCEPTED';
    public const DISPUTE_STATUS_ARBITRATION_INSUFFICIENT_EVIDENCE = 'ARBITRATION_INSUFFICIENT_EVIDENCE';
    public const DISPUTE_STATUS_UNKNOWN_DEFAULT_OPEN_API = 'unknown_default_open_api';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getDisputeTypeAllowableValues()
    {
        return [
            self::DISPUTE_TYPE_DISPUTE,
            self::DISPUTE_TYPE_CHARGEBACK,
            self::DISPUTE_TYPE_RETRIEVAL,
            self::DISPUTE_TYPE_PRE_ARBITRATION,
            self::DISPUTE_TYPE_ARBITRATION,
            self::DISPUTE_TYPE_UNKNOWN_DEFAULT_OPEN_API,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getDisputeStatusAllowableValues()
    {
        return [
            self::DISPUTE_STATUS_DISPUTE_CREATED,
            self::DISPUTE_STATUS_DISPUTE_DOCS_RECEIVED,
            self::DISPUTE_STATUS_DISPUTE_UNDER_REVIEW,
            self::DISPUTE_STATUS_DISPUTE_MERCHANT_WON,
            self::DISPUTE_STATUS_DISPUTE_MERCHANT_LOST,
            self::DISPUTE_STATUS_DISPUTE_MERCHANT_ACCEPTED,
            self::DISPUTE_STATUS_DISPUTE_INSUFFICIENT_EVIDENCE,
            self::DISPUTE_STATUS_CHARGEBACK_CREATED,
            self::DISPUTE_STATUS_CHARGEBACK_DOCS_RECEIVED,
            self::DISPUTE_STATUS_CHARGEBACK_UNDER_REVIEW,
            self::DISPUTE_STATUS_CHARGEBACK_MERCHANT_WON,
            self::DISPUTE_STATUS_CHARGEBACK_MERCHANT_LOST,
            self::DISPUTE_STATUS_CHARGEBACK_MERCHANT_ACCEPTED,
            self::DISPUTE_STATUS_CHARGEBACK_INSUFFICIENT_EVIDENCE,
            self::DISPUTE_STATUS_RETRIEVAL_CREATED,
            self::DISPUTE_STATUS_RETRIEVAL_DOCS_RECEIVED,
            self::DISPUTE_STATUS_RETRIEVAL_UNDER_REVIEW,
            self::DISPUTE_STATUS_RETRIEVAL_MERCHANT_WON,
            self::DISPUTE_STATUS_RETRIEVAL_MERCHANT_LOST,
            self::DISPUTE_STATUS_RETRIEVAL_MERCHANT_ACCEPTED,
            self::DISPUTE_STATUS_RETRIEVAL_INSUFFICIENT_EVIDENCE,
            self::DISPUTE_STATUS_PRE_ARBITRATION_CREATED,
            self::DISPUTE_STATUS_PRE_ARBITRATION_DOCS_RECEIVED,
            self::DISPUTE_STATUS_PRE_ARBITRATION_UNDER_REVIEW,
            self::DISPUTE_STATUS_PRE_ARBITRATION_MERCHANT_WON,
            self::DISPUTE_STATUS_PRE_ARBITRATION_MERCHANT_LOST,
            self::DISPUTE_STATUS_PRE_ARBITRATION_MERCHANT_ACCEPTED,
            self::DISPUTE_STATUS_PRE_ARBITRATION_INSUFFICIENT_EVIDENCE,
            self::DISPUTE_STATUS_ARBITRATION_CREATED,
            self::DISPUTE_STATUS_ARBITRATION_DOCS_RECEIVED,
            self::DISPUTE_STATUS_ARBITRATION_UNDER_REVIEW,
            self::DISPUTE_STATUS_ARBITRATION_MERCHANT_WON,
            self::DISPUTE_STATUS_ARBITRATION_MERCHANT_LOST,
            self::DISPUTE_STATUS_ARBITRATION_MERCHANT_ACCEPTED,
            self::DISPUTE_STATUS_ARBITRATION_INSUFFICIENT_EVIDENCE,
            self::DISPUTE_STATUS_UNKNOWN_DEFAULT_OPEN_API,
        ];
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
        $this->setIfExists('dispute_id', $data ?? [], null);
        $this->setIfExists('dispute_type', $data ?? [], null);
        $this->setIfExists('reason_code', $data ?? [], null);
        $this->setIfExists('reason_description', $data ?? [], null);
        $this->setIfExists('dispute_amount', $data ?? [], null);
        $this->setIfExists('created_at', $data ?? [], null);
        $this->setIfExists('respond_by', $data ?? [], null);
        $this->setIfExists('updated_at', $data ?? [], null);
        $this->setIfExists('resolved_at', $data ?? [], null);
        $this->setIfExists('dispute_status', $data ?? [], null);
        $this->setIfExists('cf_dispute_remarks', $data ?? [], null);
        $this->setIfExists('preferred_evidence', $data ?? [], null);
        $this->setIfExists('dispute_evidence', $data ?? [], null);
        $this->setIfExists('order_details', $data ?? [], null);
        $this->setIfExists('customer_details', $data ?? [], null);
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

        $allowedValues = $this->getDisputeTypeAllowableValues();
        if (!is_null($this->container['dispute_type']) && !in_array($this->container['dispute_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'dispute_type', must be one of '%s'",
                $this->container['dispute_type'],
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getDisputeStatusAllowableValues();
        if (!is_null($this->container['dispute_status']) && !in_array($this->container['dispute_status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'dispute_status', must be one of '%s'",
                $this->container['dispute_status'],
                implode("', '", $allowedValues)
            );
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
     * Gets dispute_id
     *
     * @return int|null
     */
    public function getDisputeId()
    {
        return $this->container['dispute_id'];
    }

    /**
     * Sets dispute_id
     *
     * @param int|null $dispute_id dispute_id
     *
     * @return self
     */
    public function setDisputeId($dispute_id)
    {
        if (is_null($dispute_id)) {
            throw new \InvalidArgumentException('non-nullable dispute_id cannot be null');
        }
        $this->container['dispute_id'] = $dispute_id;

        return $this;
    }

    /**
     * Gets dispute_type
     *
     * @return string|null
     */
    public function getDisputeType()
    {
        return $this->container['dispute_type'];
    }

    /**
     * Sets dispute_type
     *
     * @param string|null $dispute_type dispute_type
     *
     * @return self
     */
    public function setDisputeType($dispute_type)
    {
        if (is_null($dispute_type)) {
            throw new \InvalidArgumentException('non-nullable dispute_type cannot be null');
        }
        $allowedValues = $this->getDisputeTypeAllowableValues();
        if (!in_array($dispute_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'dispute_type', must be one of '%s'",
                    $dispute_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['dispute_type'] = $dispute_type;

        return $this;
    }

    /**
     * Gets reason_code
     *
     * @return string|null
     */
    public function getReasonCode()
    {
        return $this->container['reason_code'];
    }

    /**
     * Sets reason_code
     *
     * @param string|null $reason_code reason_code
     *
     * @return self
     */
    public function setReasonCode($reason_code)
    {
        if (is_null($reason_code)) {
            throw new \InvalidArgumentException('non-nullable reason_code cannot be null');
        }
        $this->container['reason_code'] = $reason_code;

        return $this;
    }

    /**
     * Gets reason_description
     *
     * @return string|null
     */
    public function getReasonDescription()
    {
        return $this->container['reason_description'];
    }

    /**
     * Sets reason_description
     *
     * @param string|null $reason_description reason_description
     *
     * @return self
     */
    public function setReasonDescription($reason_description)
    {
        if (is_null($reason_description)) {
            throw new \InvalidArgumentException('non-nullable reason_description cannot be null');
        }
        $this->container['reason_description'] = $reason_description;

        return $this;
    }

    /**
     * Gets dispute_amount
     *
     * @return float|null
     */
    public function getDisputeAmount()
    {
        return $this->container['dispute_amount'];
    }

    /**
     * Sets dispute_amount
     *
     * @param float|null $dispute_amount Dispute amount may differ from transaction amount for partial cases.
     *
     * @return self
     */
    public function setDisputeAmount($dispute_amount)
    {
        if (is_null($dispute_amount)) {
            throw new \InvalidArgumentException('non-nullable dispute_amount cannot be null');
        }
        $this->container['dispute_amount'] = $dispute_amount;

        return $this;
    }

    /**
     * Gets created_at
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->container['created_at'];
    }

    /**
     * Sets created_at
     *
     * @param string|null $created_at This is the time when the dispute was created.
     *
     * @return self
     */
    public function setCreatedAt($created_at)
    {
        if (is_null($created_at)) {
            throw new \InvalidArgumentException('non-nullable created_at cannot be null');
        }
        $this->container['created_at'] = $created_at;

        return $this;
    }

    /**
     * Gets respond_by
     *
     * @return string|null
     */
    public function getRespondBy()
    {
        return $this->container['respond_by'];
    }

    /**
     * Sets respond_by
     *
     * @param string|null $respond_by This is the time by which evidence should be submitted to contest the dispute.
     *
     * @return self
     */
    public function setRespondBy($respond_by)
    {
        if (is_null($respond_by)) {
            throw new \InvalidArgumentException('non-nullable respond_by cannot be null');
        }
        $this->container['respond_by'] = $respond_by;

        return $this;
    }

    /**
     * Gets updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->container['updated_at'];
    }

    /**
     * Sets updated_at
     *
     * @param string|null $updated_at This is the time when the dispute case was updated.
     *
     * @return self
     */
    public function setUpdatedAt($updated_at)
    {
        if (is_null($updated_at)) {
            throw new \InvalidArgumentException('non-nullable updated_at cannot be null');
        }
        $this->container['updated_at'] = $updated_at;

        return $this;
    }

    /**
     * Gets resolved_at
     *
     * @return string|null
     */
    public function getResolvedAt()
    {
        return $this->container['resolved_at'];
    }

    /**
     * Sets resolved_at
     *
     * @param string|null $resolved_at This is the time when the dispute case was closed.
     *
     * @return self
     */
    public function setResolvedAt($resolved_at)
    {
        if (is_null($resolved_at)) {
            throw new \InvalidArgumentException('non-nullable resolved_at cannot be null');
        }
        $this->container['resolved_at'] = $resolved_at;

        return $this;
    }

    /**
     * Gets dispute_status
     *
     * @return string|null
     */
    public function getDisputeStatus()
    {
        return $this->container['dispute_status'];
    }

    /**
     * Sets dispute_status
     *
     * @param string|null $dispute_status dispute_status
     *
     * @return self
     */
    public function setDisputeStatus($dispute_status)
    {
        if (is_null($dispute_status)) {
            throw new \InvalidArgumentException('non-nullable dispute_status cannot be null');
        }
        $allowedValues = $this->getDisputeStatusAllowableValues();
        if (!in_array($dispute_status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'dispute_status', must be one of '%s'",
                    $dispute_status,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['dispute_status'] = $dispute_status;

        return $this;
    }

    /**
     * Gets cf_dispute_remarks
     *
     * @return string|null
     */
    public function getCfDisputeRemarks()
    {
        return $this->container['cf_dispute_remarks'];
    }

    /**
     * Sets cf_dispute_remarks
     *
     * @param string|null $cf_dispute_remarks cf_dispute_remarks
     *
     * @return self
     */
    public function setCfDisputeRemarks($cf_dispute_remarks)
    {
        if (is_null($cf_dispute_remarks)) {
            throw new \InvalidArgumentException('non-nullable cf_dispute_remarks cannot be null');
        }
        $this->container['cf_dispute_remarks'] = $cf_dispute_remarks;

        return $this;
    }

    /**
     * Gets preferred_evidence
     *
     * @return \Cashfree\Model\EvidencesToContestDispute[]|null
     */
    public function getPreferredEvidence()
    {
        return $this->container['preferred_evidence'];
    }

    /**
     * Sets preferred_evidence
     *
     * @param \Cashfree\Model\EvidencesToContestDispute[]|null $preferred_evidence preferred_evidence
     *
     * @return self
     */
    public function setPreferredEvidence($preferred_evidence)
    {
        if (is_null($preferred_evidence)) {
            throw new \InvalidArgumentException('non-nullable preferred_evidence cannot be null');
        }
        $this->container['preferred_evidence'] = $preferred_evidence;

        return $this;
    }

    /**
     * Gets dispute_evidence
     *
     * @return \Cashfree\Model\Evidence[]|null
     */
    public function getDisputeEvidence()
    {
        return $this->container['dispute_evidence'];
    }

    /**
     * Sets dispute_evidence
     *
     * @param \Cashfree\Model\Evidence[]|null $dispute_evidence dispute_evidence
     *
     * @return self
     */
    public function setDisputeEvidence($dispute_evidence)
    {
        if (is_null($dispute_evidence)) {
            throw new \InvalidArgumentException('non-nullable dispute_evidence cannot be null');
        }
        $this->container['dispute_evidence'] = $dispute_evidence;

        return $this;
    }

    /**
     * Gets order_details
     *
     * @return \Cashfree\Model\OrderDetailsInDisputesEntity|null
     */
    public function getOrderDetails()
    {
        return $this->container['order_details'];
    }

    /**
     * Sets order_details
     *
     * @param \Cashfree\Model\OrderDetailsInDisputesEntity|null $order_details order_details
     *
     * @return self
     */
    public function setOrderDetails($order_details)
    {
        if (is_null($order_details)) {
            throw new \InvalidArgumentException('non-nullable order_details cannot be null');
        }
        $this->container['order_details'] = $order_details;

        return $this;
    }

    /**
     * Gets customer_details
     *
     * @return \Cashfree\Model\CustomerDetailsInDisputesEntity|null
     */
    public function getCustomerDetails()
    {
        return $this->container['customer_details'];
    }

    /**
     * Sets customer_details
     *
     * @param \Cashfree\Model\CustomerDetailsInDisputesEntity|null $customer_details customer_details
     *
     * @return self
     */
    public function setCustomerDetails($customer_details)
    {
        if (is_null($customer_details)) {
            throw new \InvalidArgumentException('non-nullable customer_details cannot be null');
        }
        $this->container['customer_details'] = $customer_details;

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


