<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\TextUI\Configuration;

use function count;
use Iterator;

/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 *
 * @template-implements Iterator<int, File>
 */
final class FileCollectionIterator implements Iterator
{
    /**
     * @psalm-var list<File>
     */
    private readonly array $files;
    private int $position = 0;

    public function __construct(FileCollection $files)
    {
        $this->files = $files->asArray();
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return $this->position < count($this->files);
    }

    public function key(): int
    {
        return $this->position;
    }

    public function current(): File
    {
        return $this->files[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }
}
