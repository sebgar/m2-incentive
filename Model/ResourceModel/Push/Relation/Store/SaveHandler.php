<?php
namespace Sga\Incentive\Model\ResourceModel\Push\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Sga\Incentive\Api\Data\PushInterface as ModelInterface;
use Sga\Incentive\Model\ResourceModel\Push as ResourceModel;

class SaveHandler implements ExtensionInterface
{
    protected $metadataPool;
    protected $resourceModel;

    public function __construct(
        MetadataPool $metadataPool,
        ResourceModel $resourceModel
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceModel = $resourceModel;
    }

    public function execute($entity, $arguments = [])
    {
        $entityMetadata = $this->metadataPool->getMetadata(ModelInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $entityMetadata->getEntityConnection();

        $oldStores = $this->resourceModel->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getStores();

        $table = $this->resourceModel->getTable('sga_incentive_push_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}
