<?php
namespace Sga\Incentive\Ui\Component\Filters\Type;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Filters\FilterModifier;

class CategoryIds extends \Magento\Ui\Component\Filters\Type\Select
{
    protected $_filterGroupBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        FilterModifier $filterModifier,
        OptionSourceInterface $optionsProvider = null,
        array $components = [],
        array $data = []
    ) {
        $this->_filterGroupBuilder = $filterGroupBuilder;

        parent::__construct($context, $uiComponentFactory, $filterBuilder, $filterModifier,$optionsProvider, $components, $data);
    }

    protected function applyFilter()
    {
        if (isset($this->filterData[$this->getName()])) {
            $value = $this->filterData[$this->getName()];

            if (!empty($value) || is_numeric($value)) {
                if (!is_array($value)) {
                    $value = [$value];
                }

                $group = $this->_filterGroupBuilder->create();
                $fields = [];
                $values = [];
                $filters = [];
                foreach ($value as $v) {
                    $filter = $this->filterBuilder->setConditionType('finset')
                        ->setField($this->getName())
                        ->setValue($v)
                        ->create();

                    $fields[] = $this->getName();
                    $values[] = $v;
                    $filters[] = $filter;
                }
                $group->setFilters($filters);

                $this->getContext()->getDataProvider()->getSearchCriteria()->setFilterGroups([$group]);
            }
        }
    }
}
