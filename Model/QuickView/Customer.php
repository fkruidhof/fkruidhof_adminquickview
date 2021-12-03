<?php
/**
 * @author Frank Kruidhof <frank.kruidhof@mailbox.org>
 */
declare(strict_types = 1);

namespace Fkruidhof\AdminQuickView\Model\QuickView;

use Fkruidhof\AdminQuickView\Api\QuickViewInterface;
use Fkruidhof\AdminQuickView\Model\Exception\QuickViewException;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\AbstractBlock;

class Customer implements QuickViewInterface
{
    private CustomerRepositoryInterface $customerRepository;
    private UrlInterface $urlBuilder;

    /**
     * @param UrlInterface                $urlBuilder
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        UrlInterface $urlBuilder,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'customer';
    }

    /**
     * @return string[]
     */
    public function getActions(): array
    {
        return ['email', 'id'];
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
            case 'email':
                $url = $this->getUrlByEmail($param);
                break;
            default:
                $url = '';
        }
        return $url;
    }

    /**
     * @param string $email
     * @return string
     * @throws QuickViewException
     */
    protected function getUrlByEmail(string $email): string
    {
        try {
            $customer = $this->customerRepository->get($email);
        } catch (NoSuchEntityException | LocalizedException $e) {
            throw new QuickViewException('failed to load customer with email ' . $email);
        }
        $id = (string)$customer->getId();
        return $this->getUrlById($id);
    }

    /**
     * @param string $id
     * @return string
     */
    protected function getUrlById(string $id): string
    {
        return $this->urlBuilder->getUrl('customer/index/edit', ['id' => $id]);
    }

    /**
     * @return AbstractBlock|null
     */
    public function getCustomBlock(): ?AbstractBlock
    {
        return null;
    }
}
