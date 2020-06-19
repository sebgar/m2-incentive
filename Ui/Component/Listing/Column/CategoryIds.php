<?php
namespace Sga\Incentive\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CategoryIds extends Column
{
    protected $_categoryCollectionFactory;

    protected $_cacheCategoryName = array();

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CollectionFactory $categoryCollectionFactory,
        array $components = [],
        array $data = []
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $categories = $this->_getCategories();

            foreach ($dataSource['data']['items'] as & $item) {
                $list = [];
                if (is_string($item['category_ids'])) {
                    $itemIds = explode(',', $item['category_ids']);
                    foreach ($itemIds as $itemId) {
                        $itemId = (int)$itemId;
                        if ($itemId > 0 && isset($categories[$itemId])) {
                            $list[] = $categories[$itemId];
                        }
                    }
                }
                $item['category_ids'] = implode(', ', $list);
            }
        }

        return $dataSource;
    }

    protected function _getCategories()
    {
        $list = [];

        $categories = $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('name');

        foreach ($categories as $category) {
            if ($category->getId() != \Magento\Catalog\Model\Category::TREE_ROOT_ID) {
                $list[$category->getId()] = $this->_getCategoryPathName($category, $categories);
            }
        }

        asort($list);

        return $list;
    }

    protected function _getCategoryPathName($category, $categories)
    {
        $name = array();
        $path = explode('/', $category->getPath());

        // remove root category
        array_shift($path);

        foreach ($path as $catId) {
            if (isset($this->_cacheCategoryName[$catId])) {
                $name[] = $this->_cacheCategoryName[$catId];
            } else {
                $cat = $categories->getItemById($catId);
                if ((int)$cat->getId() > 0) {
                    $this->_cacheCategoryName[$catId] = $cat->getName();
                    $name[] = $cat->getName();
                } else {
                    $name[] = '-';
                }
            }
        }

        return implode(' > ', $name);
    }
}
