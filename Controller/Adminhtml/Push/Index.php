<?php
namespace Sga\Incentive\Controller\Adminhtml\Push;

use Sga\Incentive\Controller\Adminhtml\Push as ParentClass;

class Index extends ParentClass
{
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $this->initPage($resultPage);

        return $resultPage;
    }
}
