<?php
namespace Sga\Incentive\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Sga\Incentive\Api\Data\PushInterface as ModelInterface;
use Sga\Incentive\Model\ResourceModel\Push as ResourceModel;

class Push extends AbstractModel implements IdentityInterface, ModelInterface
{
    const CACHE_TAG = 'incentive_push';

    protected $_eventPrefix = 'incentive_push';

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getPushId()
    {
        return $this->getData(self::PUSH_ID);
    }

    public function setPushId($id)
    {
        return $this->setData(self::PUSH_ID, $id);
    }

    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    public function setIsActive($value)
    {
        return $this->setData(self::IS_ACTIVE, $value);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }

    public function getPositions()
    {
        return $this->getData(self::POSITIONS);
    }

    public function setPositions($value)
    {
        return $this->setData(self::POSITIONS, $value);
    }

    public function getSize()
    {
        return $this->getData(self::SIZE);
    }

    public function setSize($value)
    {
        return $this->setData(self::SIZE, $value);
    }

    public function getBlockCmsId()
    {
        return $this->getData(self::BLOCK_CMS_ID);
    }

    public function setBlockCmsId($value)
    {
        return $this->setData(self::BLOCK_CMS_ID, $value);
    }

    public function getHtml()
    {
        return $this->getData(self::HTML);
    }

    public function setHtml($value)
    {
        return $this->setData(self::HTML, $value);
    }

    public function getCategoryIds()
    {
        return $this->getData(self::CATEGORY_IDS);
    }

    public function setCategoryIds($value)
    {
        return $this->setData(self::CATEGORY_IDS, $value);
    }

    public function getAllCategories()
    {
        return $this->getData(self::ALL_CATEGORIES);
    }

    public function setAllCategories($value)
    {
        return $this->setData(self::ALL_CATEGORIES, $value);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($value)
    {
        return $this->setData(self::CREATED_AT, $value);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($value)
    {
        return $this->setData(self::UPDATED_AT, $value);
    }

    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }
}
