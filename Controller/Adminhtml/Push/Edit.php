<?php
namespace Sga\Incentive\Controller\Adminhtml\Push;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Sga\Incentive\Controller\Adminhtml\Push as ParentClass;

class Edit extends ParentClass implements HttpGetActionInterface
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('push_id');
        $model = $this->_modelFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This push no longer exists.'));

                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $resultPage = $this->_resultPageFactory->create();
        $this->initPage($resultPage)
            ->addBreadcrumb(
            $id ? __('Edit Push') : __('New Push'),
            $id ? __('Edit Push') : __('New Push')
            );
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? 'Push #'.$model->getId() : __('New Push'));

        return $resultPage;
    }
}
