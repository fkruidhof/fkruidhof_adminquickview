<?php
/**
 * @author Frank Kruidhof <frank.kruidhof@mailbox.org>
 */
declare(strict_types = 1);

namespace Fkruidhof\AdminQuickView\Model\QuickView;

use Fkruidhof\AdminQuickView\Api\QuickViewInterface;
use Fkruidhof\AdminQuickView\Model\Exception\QuickViewException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\AbstractBlock;

class Product implements QuickViewInterface
{
    protected ProductRepositoryInterface $productRepository;
    private UrlInterface $urlBuilder;

    /**
     * @param UrlInterface               $urlBuilder
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'product';
    }

    /**
     * @return string[]
     */
    public function getActions(): array
    {
        return ['sku', 'id'];
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
            case 'sku':
                $url = $this->getUrlBySku($param);
                break;
            default:
                $url = '';
        }
        return $url;
    }

    /**
     * @param string $sku
     * @return string
     * @throws QuickViewException
     */
    protected function getUrlBySku(string $sku): string
    {
        try {
            $product = $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            throw new QuickViewException('failed to load product with sku ' . $sku);
        }
        $id = (string) $product->getId();
        return $this->getUrlById($id);
    }

    /**
     * @param string $id
     * @return string
     */
    protected function getUrlById(string $id): string
    {
        return $this->urlBuilder->getUrl('catalog/product/edit', ['id' => $id]);
    }

    /**
     * @return AbstractBlock|null
     */
    public function getCustomBlock(): ?AbstractBlock
    {
        return null;
    }
}
