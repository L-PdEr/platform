<?php declare(strict_types=1);

namespace Shopware\Api\Mail\Collection;

use Shopware\Api\Entity\EntityCollection;
use Shopware\Api\Mail\Struct\MailAttachmentBasicStruct;

class MailAttachmentBasicCollection extends EntityCollection
{
    /**
     * @var MailAttachmentBasicStruct[]
     */
    protected $elements = [];

    public function get(string $uuid): ? MailAttachmentBasicStruct
    {
        return parent::get($uuid);
    }

    public function current(): MailAttachmentBasicStruct
    {
        return parent::current();
    }

    public function getMailUuids(): array
    {
        return $this->fmap(function (MailAttachmentBasicStruct $mailAttachment) {
            return $mailAttachment->getMailUuid();
        });
    }

    public function filterByMailUuid(string $uuid): self
    {
        return $this->filter(function (MailAttachmentBasicStruct $mailAttachment) use ($uuid) {
            return $mailAttachment->getMailUuid() === $uuid;
        });
    }

    public function getMediaUuids(): array
    {
        return $this->fmap(function (MailAttachmentBasicStruct $mailAttachment) {
            return $mailAttachment->getMediaUuid();
        });
    }

    public function filterByMediaUuid(string $uuid): self
    {
        return $this->filter(function (MailAttachmentBasicStruct $mailAttachment) use ($uuid) {
            return $mailAttachment->getMediaUuid() === $uuid;
        });
    }

    public function getShopUuids(): array
    {
        return $this->fmap(function (MailAttachmentBasicStruct $mailAttachment) {
            return $mailAttachment->getShopUuid();
        });
    }

    public function filterByShopUuid(string $uuid): self
    {
        return $this->filter(function (MailAttachmentBasicStruct $mailAttachment) use ($uuid) {
            return $mailAttachment->getShopUuid() === $uuid;
        });
    }

    protected function getExpectedClass(): string
    {
        return MailAttachmentBasicStruct::class;
    }
}
