<?php
/**
 * @author Frank Kruidhof <frank.kruidhof@mailbox.org>
 */
declare(strict_types = 1);

namespace Fkruidhof\AdminQuickView\Model;

use Fkruidhof\AdminQuickView\Api\QuickViewInterface;
use Fkruidhof\AdminQuickView\Model\Exception\QuickViewException;

class QuickViewPool
{
    private array $quickViews;

    /**
     * @param QuickViewInterface[] $quickViews
     * @throws QuickViewException
     */
    public function __construct(array $quickViews = [])
    {
        foreach ($quickViews as $quickView) {
            if (!$quickView instanceof QuickViewInterface) {
                throw new QuickViewException('quickView not an instance of CommandInterface');
            }
        }
        $this->quickViews = $quickViews;
    }

    /**
     * @return QuickViewInterface[]
     */
    public function getQuickViews(): array
    {
        return $this->quickViews;
    }
}
