<?php
namespace Sga\Incentive\Block;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Widget\Model\Template\Filter as CmsFilter;
use Magento\Catalog\Model\Product\ProductList\Toolbar;
use Magento\Catalog\Helper\Product\ProductList as HelperProductList;
use Sga\Incentive\Model\ResourceModel\Push\CollectionFactory as PushCollectionFactory;

class Push extends Template
{
    protected $_storeManager;
    protected $_registry;
    protected $_pushCollectionFactory;
    protected $_cmsFilter;
    protected $_toolbar;
    protected $_helperProductList;
    protected $_jsonSerializer;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Registry $registry,
        PushCollectionFactory $pushCollectionFactory,
        CmsFilter $cmsFilter,
        Toolbar $toolbar,
        HelperProductList $helperProductList,
        Json $jsonSerializer
    ) {
        $this->_storeManager = $storeManager;
        $this->_registry = $registry;
        $this->_pushCollectionFactory = $pushCollectionFactory;
        $this->_cmsFilter = $cmsFilter;
        $this->_toolbar = $toolbar;
        $this->_helperProductList = $helperProductList;
        $this->_jsonSerializer = $jsonSerializer;

        parent::__construct($context);
    }

    public function getPushs()
    {
        $list = [];

        $category = $this->_registry->registry('current_category');
        if ($category instanceof \Magento\Catalog\Model\Category && (int)$category->getId() > 0) {
            $page = $this->_toolbar->getCurrentPage();

            $list = $this->_pushCollectionFactory->create()
                ->addIsActiveFilter(true)
                ->addCategoriesFilter([$category->getId()])
                ->addCategoriesExcludedFilter([$category->getId()])
                ->addStoreFilter($this->_storeManager->getStore())
                ->getByPosition($page);
        }
        return $list;
    }

    public function getCmsFilter()
    {
        return $this->_cmsFilter;
    }

    public function getJsonSerializer()
    {
        return $this->_jsonSerializer;
    }
}
