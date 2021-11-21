<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\MonorepoBuilder\Git;

use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

final class TagResolver implements TagResolverInterface
{
    private const COMMAND = ['git', 'tag', '-l', '--sort=committerdate'];


    public function __construct(private ProcessRunner $processRunner)
    {
    }

    public function resolve($gitDirectory): ?string
    {
        $tagList = $this->parseTags($this->processRunner->run(self::COMMAND, $gitDirectory));

        $theMostRecentTag = (string)\array_pop($tagList);

        if ($theMostRecentTag === '') {
            return null;
        }

        return $theMostRecentTag;
    }


    private function parseTags(string $commandResult): array
    {
        $tags = \trim($commandResult);
        // Remove all "\r" chars in case the CLI env like the Windows OS.
        // Otherwise (ConEmu, git bash, mingw cli, e.g.), leave as is.
        $normalizedTags = \str_replace("\r", '', $tags);

        return \array_filter(
            \explode("\n", $normalizedTags),
            fn (string $tag) => false === str_starts_with($tag, 'helm-chart')
        );
    }
}
