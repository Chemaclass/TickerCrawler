<?php

declare(strict_types=1);

namespace Chemaclass\StockTickerTests\Unit\Domain\WriteModel;

use Chemaclass\StockTicker\Domain\WriteModel\CompanyName;
use Chemaclass\StockTicker\Domain\WriteModel\Currency;
use Chemaclass\StockTicker\Domain\WriteModel\MarketCap;
use Chemaclass\StockTicker\Domain\WriteModel\News;
use Chemaclass\StockTicker\Domain\WriteModel\Quote;
use Chemaclass\StockTicker\Domain\WriteModel\RegularMarketChange;
use Chemaclass\StockTicker\Domain\WriteModel\RegularMarketChangePercent;
use Chemaclass\StockTicker\Domain\WriteModel\RegularMarketPrice;
use Chemaclass\StockTicker\Domain\WriteModel\Trend;
use PHPUnit\Framework\TestCase;

final class QuoteTest extends TestCase
{
    private Quote $quote;

    protected function setUp(): void
    {
        $this->quote = (new Quote())->fromArray([
            'symbol' => 'AMZN',
            'companyName' => [
                'shortName' => 'Short Company name, Inc.',
                'longName' => 'Long Company name, Inc.',
            ],
            'currency' => [
                'currency' => 'USD',
                'symbol' => '$',
            ],
            'url' => 'https://example.url.com',
            'regularMarketPrice' => [
                'raw' => 629.999,
                'fmt' => '629.99',
            ],
            'regularMarketChange' => [
                'raw' => -3.2900085,
                'fmt' => '-3.29',
            ],
            'regularMarketChangePercent' => [
                'raw' => -1.8199171,
                'fmt' => '-1.82%',
            ],
            'marketCap' => [
                'raw' => 797834477568,
                'fmt' => '797.834B',
                'longFmt' => '797,834,477,568',
            ],
            'lastTrend' => [
                [
                    'period' => '0m',
                    'strongBuy' => 11,
                    'buy' => 12,
                    'hold' => 13,
                    'sell' => 14,
                    'strongSell' => 15,
                ],
                [
                    'period' => '-1m',
                    'strongBuy' => 21,
                    'buy' => 22,
                    'hold' => 23,
                    'sell' => 24,
                    'strongSell' => 25,
                ],
            ],
            'latestNews' => [
                [
                    'datetime' => 'example datetime',
                    'timezone' => 'example timezone',
                    'url' => 'example url',
                    'title' => 'example title',
                    'summary' => 'example summary',
                    'publisher' => 'example publisher',
                    'source' => 'example source',
                    'images' => [
                        [
                            'url' => 'example.img.url',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_company_name(): void
    {
        self::assertEquals(
            (new CompanyName())
                ->setShortName('Short Company name, Inc.')
                ->setLongName('Long Company name, Inc.'),
            $this->quote->getCompanyName(),
        );
    }

    public function test_symbol(): void
    {
        self::assertEquals('AMZN', $this->quote->getSymbol());
    }

    public function test_currency(): void
    {
        self::assertEquals(
            (new Currency())
                ->setSymbol('$')
                ->setCurrency('USD'),
            $this->quote->getCurrency(),
        );
    }

    public function test_url(): void
    {
        self::assertEquals('https://example.url.com', $this->quote->getUrl());
    }

    public function test_regular_market_price(): void
    {
        self::assertEquals(
            (new RegularMarketPrice())
                ->setFmt('629.99')
                ->setRaw(629.999),
            $this->quote->getRegularMarketPrice(),
        );
    }

    public function test_regular_market_change(): void
    {
        self::assertEquals(
            (new RegularMarketChange())
                ->setFmt('-3.29')
                ->setRaw(-3.2900085),
            $this->quote->getRegularMarketChange(),
        );
    }

    public function test_regular_market_change_percent(): void
    {
        self::assertEquals(
            (new RegularMarketChangePercent())
                ->setFmt('-1.82%')
                ->setRaw(-1.8199171),
            $this->quote->getRegularMarketChangePercent(),
        );
    }

    public function test_market_cap(): void
    {
        self::assertEquals(
            (new MarketCap())
                ->setRaw(797834477568)
                ->setFmt('797.834B')
                ->setLongFmt('797,834,477,568'),
            $this->quote->getMarketCap(),
        );
    }

    public function test_last_trend(): void
    {
        self::assertEquals([
            (new Trend())
                ->setPeriod('0m')
                ->setStrongBuy(11)
                ->setBuy(12)
                ->setHold(13)
                ->setSell(14)
                ->setStrongSell(15),
            (new Trend())
                ->setPeriod('-1m')
                ->setStrongBuy(21)
                ->setBuy(22)
                ->setHold(23)
                ->setSell(24)
                ->setStrongSell(25),
        ], $this->quote->getLastTrend());
    }

    public function test_latest_news(): void
    {
        self::assertEquals([
            (new News())
                ->setDatetime('example datetime')
                ->setTimezone('example timezone')
                ->setUrl('example url')
                ->setTitle('example title')
                ->setPublisher('example publisher')
                ->setSource('example source')
                ->setSummary('example summary')
                ->setImages([
                    [
                        'url' => 'example.img.url',
                    ],
                ]),
        ], $this->quote->getLatestNews());
    }
}
