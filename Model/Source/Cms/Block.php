<?php
namespace Sga\Incentive\Model\Source\Cms;

use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Block implements OptionSourceInterface
{
    protected $options;
    protected $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->collectionFactory->create()->toOptionArray();
            $this->options = array_merge([['value' => '', 'label' => '-']], $this->options);
        }

        return $this->options;
    }
}
