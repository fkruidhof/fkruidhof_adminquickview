<?php
/**
 * @author Frank Kruidhof <frank.kruidhof@mailbox.org>
 */
declare(strict_types = 1);

namespace Fkruidhof\AdminQuickView\Controller\Adminhtml\QuickView;

use Fkruidhof\AdminQuickView\Block\Adminhtml\Menu\QuickView;
use Fkruidhof\AdminQuickView\Model\QuickViewPool;
use Fkruidhof\AdminQuickView\Model\Exception\QuickViewException;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Message\ManagerInterface;

class Index extends Action
{

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;
    /**
     * @var QuickViewPool
     */
    private QuickViewPool $commandPool;

    /**
     *
     * /**
     *
     * @param Context          $context
     * @param PageFactory      $resultPageFactory
     * @param QuickViewPool    $commandPool
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Context          $context,
        PageFactory      $resultPageFactory,
        QuickViewPool    $commandPool,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->commandPool = $commandPool;
        $this->messageManager = $messageManager;
    }

    /**
     * @return Redirect
     * @throws \Exception
     */
    public function execute(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('');
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect;
        }

        try {
            $newRedirect = $this->executeCommand();
            if ($newRedirect) {
                $resultRedirect = $newRedirect;
            }
        } catch (\RuntimeException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect;
    }

    /**
     * @return null|Redirect
     * @throws \Exception
     */
    protected function executeCommand(): ?Redirect
    {
        $request = $this->getRequest();
        $formParam = $this->sanitize(
            $request->getParam(QuickView::FORM_INPUT_NAME)
        );
        if (!$formParam) {
            return null;
        }

        $formCommand = $this->sanitize(
            (string) $request->getParam(QuickView::FORM_COMMAND_INPUT_NAME)
        );
        $formAction = $this->sanitize(
            (string) $request->getParam(QuickView::FORM_COMMAND_ACTION_INPUT_NAME)
        );

        foreach ($this->commandPool->getQuickViews() as $command) {
            if ($command->getName() !== $formCommand) {
                continue;
            }

            try {
                $url = $command->getUrl($formAction, $formParam);
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setUrl($url);
                return $resultRedirect;
            } catch (QuickViewException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return null;
            }
        }
        $this->messageManager->addErrorMessage(sprintf('Unknown command "%s"', $formCommand));
        return null;
    }

    /**
     * @return boolean
     * phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _isAllowed()
    {
        $isAllowed = $this->_authorization->isAllowed('Fkruidhof_AdminQuickView::quickview');
        if (!$isAllowed) {
            $this->messageManager->addErrorMessage('permission denied for current user');
        }
        return $isAllowed;
    }
    //phpcs:enable

    /**
     * @param string $formInput
     * @return string
     */
    protected function sanitize(string $formInput): string
    {
        return (string) preg_replace('/[^a-zA-Z0-9 @._:\-\/]/i', '', $formInput);
    }
}
