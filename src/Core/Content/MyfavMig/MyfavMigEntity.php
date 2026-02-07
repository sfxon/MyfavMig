<?php declare(strict_types=1);

namespace Myfav\Mig\Core\Content\MyfavMig;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyfavMigEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;
    protected int $pos;
    protected int $state;
    protected $settings;

    // $name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    // pos
    public function getPos(): int
    {
        return $this->pos;
    }

    public function setPos(int $pos): void
    {
        $this->pos = $pos;
    }

    // state
    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): void
    {
        $this->state = $state;
    }

    // settings
    /**
     * @return array<string, mixed>|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }
}
