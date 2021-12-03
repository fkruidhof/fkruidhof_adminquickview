<?php
/**
 * @author Frank Kruidhof <frank.kruidhof@mailbox.org>
 */
declare(strict_types = 1);

namespace Fkruidhof\AdminQuickView\Plugin\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Backend\Block\Menu;
use Magento\Backend\Model\Menu as MenuModel;
use Magento\Framework\View\Layout;

class MenuPlugin
{
    private Layout $layout;

    /**
     * @param Layout $layout
     */
    public function __construct(
        Layout $layout
    ) {
        $this->layout = $layout;
    }

    /**
     * @param Menu      $subject
     * @param string    $result
     * @param MenuModel $menu
     * @return string
     * @throws \Exception
     * phpcs:disable Squiz.Commenting.FunctionComment.ScalarTypeHintMissing,Squiz.Commenting.FunctionComment.TypeHintMissing
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterRenderNavigation(Menu $subject, $result, $menu)
    {
        if (!$menu->getFirstAvailable() ||
            $menu->getFirstAvailable()->getId() !== 'Fkruidhof_AdminQuickView::input_placeholder'
        ) {
            return $result;
        }
        return $this->renderQuickViewInputFields();
    }
    //phpcs:enable

    /**
     * Render order items block.
     *
     * @return string
     * @throws \Exception
     */
    private function renderQuickViewInputFields(): string
    {
        $quickViewMenuBlock = $this->layout->createBlock('Fkruidhof\AdminQuickView\Block\Adminhtml\Menu\QuickView');
        if (!$quickViewMenuBlock instanceof AbstractBlock) {
            throw new \Exception('Could not load Magento Commander block');
        }
        return $quickViewMenuBlock->toHtml();
    }
}
