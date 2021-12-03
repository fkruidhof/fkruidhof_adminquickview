<?php
/**
 * @author Frank Kruidhof <frank.kruidhof@mailbox.org>
 */
declare(strict_types = 1);

namespace Fkruidhof\AdminQuickView\Model\QuickView;

use Fkruidhof\AdminQuickView\Api\QuickViewInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Sales\Api\OrderRepositoryInterface;
use Fkruidhof\AdminQuickView\Model\Exception\QuickViewException;

class Order implements QuickViewInterface
{
    protected OrderRepositoryInterface $orderRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    private UrlInterface $urlBuilder;

    /**
     * @param UrlInterface             $urlBuilder
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder    $searchCriteriaBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'order';
    }

    /**
     * @return string[]
     */
    public function getActions(): array
    {
        return ['order number', 'id'];
    }

    /**
     * @param string $action
     * @param string $param
     * @return string
     * @throws QuickViewException
     */
    public function getUrl(string $action, string $param): string
    {
        switch ($action) {
            case 'id':
                $url = $this->getUrlById($param);
                break;
            case 'order number':
                $url = $this->getUrlByIncrementId($param);
                break;
            default:
                $url = '';
        }
        return $url;
    }

    /**
     * @param string $incrementId
     * @return string
     * @throws QuickViewException
     */
    protected function getUrlByIncrementId(string $incrementId): string
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $incrementId)
            ->create();
        $orders = $this->orderRepository->getList($searchCriteria);
        foreach ($orders->getItems() as $order) {
            $id =  (string) $order->getEntityId();
            return $this->getUrlById($id);
        }
        throw new QuickViewException('failed to load order with increment id ' . $incrementId);
    }

    /**
     * @param string $id
     * @return string
     */
    protected function getUrlById(string $id): string
    {
        return $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $id]);
    }

    /**
     * @return AbstractBlock|null
     */
    public function getCustomBlock(): ?AbstractBlock
    {
        return null;
    }
}
