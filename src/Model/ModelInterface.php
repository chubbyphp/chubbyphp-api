<?php

declare(strict_types=1);

namespace Chubbyphp\Api\Model;

interface ModelInterface extends \JsonSerializable
{
    public function getId(): string;

    public function getCreatedAt(): \DateTimeInterface;

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void;

    /**
     * @return null|\DateTime|\DateTimeImmutable
     */
    public function getUpdatedAt(): ?\DateTimeInterface;

    /**
     * @return array{id: string, createdAt: \DateTimeInterface, updatedAt: null|\DateTimeInterface, ...}
     */
    public function jsonSerialize(): array;
}
