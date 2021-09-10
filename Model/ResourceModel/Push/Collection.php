<?php
namespace Sga\Incentive\Model\ResourceModel\Push;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Sga\Incentive\Api\Data\PushInterface as ModelInterface;
use Sga\Incentive\Model\Push as Model;
use Sga\Incentive\Model\ResourceModel\Push as ResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'push_id';

    protected $storeManager;
    protected $metadataPool;
    protected $_positionsFilter;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->storeManager = $storeManager;
        $this->metadataPool = $metadataPool;

        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);

        $this->_map['fields']['push_id'] = 'main_table.push_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    public function addIsActiveFilter($flag = true)
    {
        $this->addFieldToFilter('is_active', (int)$flag);
        return $this;
    }

    public function addCategoriesFilter(array $categoryIds)
    {
        $expr = ['all_categories = 1'];
        foreach ($categoryIds as $categoryId) {
            $expr[] = 'FIND_IN_SET('.$categoryId.', category_ids)';
        }

        if (count($expr) > 0) {
            $this->getSelect()->where(new \Zend_Db_Expr(implode(' OR ', $expr)));
        }
        return $this;
    }

    public function addCategoriesExcludedFilter(array $categoryIds)
    {
        foreach ($categoryIds as $categoryId) {
            $expr[] = 'NOT FIND_IN_SET('.$categoryId.', category_ids_excluded)';
        }

        if (count($expr) > 0) {
            $this->getSelect()->where(new \Zend_Db_Expr(implode(' OR ', $expr)));
        }
        return $this;
    }

    public function addStoreFilter($store = null, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $store = $this->storeManager->getStore($store);
            $this->_performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(ModelInterface::class);
        $this->_performAfterLoad('sga_incentive_push_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(ModelInterface::class);
        $this->_joinStoreRelationTable('sga_incentive_push_store', $entityMetadata->getLinkField());
    }

    protected function _performAfterLoad($tableName, $linkField)
    {
        $linkedIds = $this->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['table_store' => $this->getTable($tableName)])
                ->where('table_store.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[$linkField]][] = $storeData['store_id'];
                }

                foreach ($this as $item) {
                    $linkedId = $item->getData($linkField);
                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }
                    $storeIdKey = array_search(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);
                    if ($storeIdKey !== false) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = current($storesData[$linkedId]);
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', $storesData[$linkedId]);
                }
            }
        }
    }

    protected function _performAddStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');
    }

    protected function _joinStoreRelationTable($tableName, $linkField)
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable($tableName)],
                'main_table.' . $linkField . ' = store_table.' . $linkField,
                []
            )->group(
                'main_table.' . $linkField
            );
        }
        parent::_renderFiltersBefore();
    }

    public function getByPosition($page)
    {
        $lines = [];
        foreach ($this->getItems() as $item) {
            if ( (bool)$item->getRepeat() || (!(bool)$item->getRepeat() && $page === 1)) {
                $positions = is_array($item->getPositions()) ? $item->getPositions() : explode(',', $item->getPositions());
                foreach ($positions as $position) {
                    $lines[(int)$position][] = $item;
                }
            }
        }

        ksort($lines);
        return $lines;
    }
}
