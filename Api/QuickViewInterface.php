<?php
/**
 * @author Frank Kruidhof <frank.kruidhof@mailbox.org>
 */
declare(strict_types = 1);

namespace Fkruidhof\AdminQuickView\Api;

use Fkruidhof\AdminQuickView\Model\Exception\QuickViewException;
use Magento\Framework\View\Element\AbstractBlock;

interface QuickViewInterface
{
    /**
     * @param string $action
     * @param string $param
     * @return string
     * @throws QuickViewException
     */
    public function getUrl(string $action, string $param): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string[]
     */
    public function getActions(): array;

    /**
     * @return AbstractBlock|null
     */
    public function getCustomBlock(): ?AbstractBlock;
}
