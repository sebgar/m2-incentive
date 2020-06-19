<?php
namespace Sga\Incentive\Controller\Adminhtml\Push;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Sga\Incentive\Controller\Adminhtml\Push as ParentClass;

class NewAction extends ParentClass implements HttpGetActionInterface
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
