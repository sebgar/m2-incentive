<?php
namespace Sga\Incentive\Controller\Adminhtml\Push;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Sga\Incentive\Controller\Adminhtml\Push as ParentClass;

class Delete extends ParentClass implements HttpPostActionInterface
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam('push_id');
        if ($id) {
            try {
                $model = $this->_modelFactory->create();
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You deleted the push.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['push_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a push to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
