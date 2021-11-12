<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class HasuraEvents
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'json')]
    private array $payload = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}