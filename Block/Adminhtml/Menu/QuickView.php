<?php
/**
 * @author Frank Kruidhof <frank.kruidhof@mailbox.org>
 */
declare(strict_types = 1);

namespace Fkruidhof\AdminQuickView\Block\Adminhtml\Menu;

use Fkruidhof\AdminQuickView\Api\QuickViewInterface;
use Fkruidhof\AdminQuickView\Model\QuickViewPool;
use Magento\Backend\Block\Template;
use Magento\Backend\Model\UrlInterface;

class QuickView extends Template
{
    private UrlInterface $url;
    private QuickViewPool $quickViewPool;

    /**
     * @param Template\Context $context
     * @param UrlInterface     $url
     * @param QuickViewPool    $quickViewPool
     */
    public function __construct(
        Template\Context $context,
        UrlInterface $url,
        QuickViewPool $quickViewPool
    ) {
        parent::__construct($context);
        $this->url = $url;
        $this->quickViewPool = $quickViewPool;
    }

    public const FORM_INPUT_NAME = 'fkruidhof_adminquickview_input';
    public const FORM_COMMAND_INPUT_NAME = 'fkruidhof_adminquickview_command';
    public const FORM_COMMAND_ACTION_INPUT_NAME = 'fkruidhof_adminquickview_command_action';
    /**
     * @var string
     */
    //phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
    protected $_template = 'Fkruidhof_AdminQuickView::menu/quickViewMenu.phtml';

    /**
     * @return string
     */
    public function getSubmitUrl(): string
    {
        return $this->url->getUrl('quickview/quickview');
    }

    /**
     * @return QuickViewInterface[]
     */
    public function getQuickViews(): array
    {
        return $this->quickViewPool->getQuickViews();
    }
}
