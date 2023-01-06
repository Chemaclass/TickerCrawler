<?php

declare(strict_types=1);

namespace Chemaclass\StockTicker\Domain\Crawler\Site\Shared;

use DateTimeImmutable;
use DateTimeZone;

use function array_slice;

final class NewsNormalizer implements NewsNormalizerInterface
{
    private const NORMALIZED_DATETIME_FORMAT = 'Y-m-d H:i:s';

    private const DEFAULT_MAX_TEXT_LENGTH_CHARS = 1500;

    private DateTimeZone $timeZone;

    private ?int $maxNewsToFetch;

    private int $maxTextLengthChars;

    public function __construct(
        DateTimeZone $timeZone,
        ?int $maxNewsToFetch = null,
        int $maxTextLengthChars = self::DEFAULT_MAX_TEXT_LENGTH_CHARS,
    ) {
        $this->timeZone = $timeZone;
        $this->maxNewsToFetch = $maxNewsToFetch;
        $this->maxTextLengthChars = $maxTextLengthChars;
    }

    public function normalizeDateTime(DateTimeImmutable $dt): string
    {
        $dt->setTimeZone($this->timeZone);

        return $dt->format(self::NORMALIZED_DATETIME_FORMAT);
    }

    public function getTimeZoneName(): string
    {
        return $this->timeZone->getName();
    }

    public function normalizeText(string $text): string
    {
        if (mb_strlen($text) < $this->maxTextLengthChars) {
            return $text;
        }

        return mb_strimwidth($text, 0, $this->maxTextLengthChars, '...');
    }

    public function limitByMaxToFetch(array $info): array
    {
        if ($this->maxNewsToFetch === null) {
            return $info;
        }

        return array_slice($info, 0, $this->maxNewsToFetch);
    }
}
