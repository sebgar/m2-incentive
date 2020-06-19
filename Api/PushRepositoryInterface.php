<?php
namespace Sga\Incentive\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Sga\Incentive\Api\Data\PushInterface as ModelInterface;

interface PushRepositoryInterface
{
    public function save(ModelInterface $model);

    public function getById($id);

    public function getList(SearchCriteriaInterface $searchCriteria);

    public function delete(ModelInterface $model);

    public function deleteById($id);
}
