<?php

declare(strict_types=1);

namespace Chemaclass\StockTicker\Domain\Notifier\Policy\Condition;

use Chemaclass\StockTicker\Domain\Notifier\Policy\PolicyConditionInterface;
use Chemaclass\StockTicker\Domain\WriteModel\News;
use Chemaclass\StockTicker\Domain\WriteModel\Quote;

final class RecentNewsWasFound implements PolicyConditionInterface
{
    /**
     * @var array<string,string>
     * For example ['Symbol' => 'datetime']
     */
    private static array $cacheOldestBySymbol = [];

    public function __invoke(Quote $quote): bool
    {
        if ($quote->getSymbol() === null) {
            return false;
        }

        $current = $this->findLatestDateTimeFromNews($quote);
        $previous = self::$cacheOldestBySymbol[$quote->getSymbol()] ?? '';

        self::$cacheOldestBySymbol[$quote->getSymbol()] = $current;

        return $current > $previous;
    }

    private function findLatestDateTimeFromNews(Quote $quote): string
    {
        $reduced = array_reduce(
            $quote->getLatestNews(),
            static function (?News $carry, News $current): News {
                if ($carry === null) {
                    return $current;
                }

                return $carry->getDatetime() > $current->getDatetime()
                    ? $carry
                    : $current;
            },
        );

        return $reduced->getDatetime() ?? '';
    }
}
