<?php
namespace Sga\Incentive\Api\Data;

interface PushInterface
{
    const PUSH_ID = 'push_id';
    const IS_ACTIVE = 'is_active';
    const NAME = 'name';
    const POSITIONS = 'positions';
    const SIZE = 'size';
    const BLOCK_CMS_ID = 'block_cms_id';
    const HTML = 'html';
    const CATEGORY_IDS = 'category_ids';
    const ALL_CATEGORIES = 'all_categories';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get push id
     *
     * @return int|null
     */
    public function getPushId();

    /**
     * Set push id
     *
     * @param int $id
     * @return PushInterface
     */
    public function setPushId($id);
    
    /**
     * Get is_active
     *
     * @return bool|null
     */
    public function getIsActive();

    /**
     * Set is_active
     *
     * @param bool $value
     * @return PushInterface
     */
    public function setIsActive($value);
    
    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $value
     * @return PushInterface
     */
    public function setName($value);
    
    /**
     * Get positions
     *
     * @return string|null
     */
    public function getPositions();

    /**
     * Set positions
     *
     * @param string $value
     * @return PushInterface
     */
    public function setPositions($value);
    
    /**
     * Get size
     *
     * @return string|null
     */
    public function getSize();

    /**
     * Set size
     *
     * @param string $value
     * @return PushInterface
     */
    public function setSize($value);
    
    /**
     * Get block_cms_id
     *
     * @return int|null
     */
    public function getBlockCmsId();

    /**
     * Set block_cms_id
     *
     * @param int $value
     * @return PushInterface
     */
    public function setBlockCmsId($value);
    
    /**
     * Get html
     *
     * @return string|null
     */
    public function getHtml();

    /**
     * Set html
     *
     * @param string $value
     * @return PushInterface
     */
    public function setHtml($value);
    
    /**
     * Get category_ids
     *
     * @return string|null
     */
    public function getCategoryIds();

    /**
     * Set category_ids
     *
     * @param string $value
     * @return PushInterface
     */
    public function setCategoryIds($value);
    
    /**
     * Get all_categories
     *
     * @return int|null
     */
    public function getAllCategories();

    /**
     * Set all_categories
     *
     * @param int $value
     * @return PushInterface
     */
    public function setAllCategories($value);
    
    /**
     * Get created_at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     *
     * @param string $value
     * @return PushInterface
     */
    public function setCreatedAt($value);
    
    /**
     * Get updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     *
     * @param string $value
     * @return PushInterface
     */
    public function setUpdatedAt($value);
    
}
