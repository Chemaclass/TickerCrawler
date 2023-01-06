<?php

declare(strict_types=1);

namespace Chemaclass\StockTickerTests\Unit\Domain\Crawler\Site\FinanceYahoo\JsonExtractor\StreamStore;

use Chemaclass\StockTicker\Domain\Crawler\Site\FinanceYahoo\JsonExtractor\StreamStore\News;
use Chemaclass\StockTicker\Domain\Crawler\Site\Shared\NewsNormalizer;
use DateTimeZone;
use Generator;
use PHPUnit\Framework\TestCase;

final class NewsTest extends TestCase
{
    private const EXAMPLE_UNIX_PUBTIME = 1607651748000;

    private const EXAMPLE_FORMATTED_DATETIME = '2020-12-11 01:55:48';

    private const EXAMPLE_TIMEZONE = 'Europe/Berlin';

    /**
     * @dataProvider providerExtractFromJson
     */
    public function test_extract_from_json(array $allItems, array $expected): void
    {
        $json = $this->createJsonWithItems($allItems);
        $news = new News(
            new NewsNormalizer(new DateTimeZone(self::EXAMPLE_TIMEZONE)),
        );

        self::assertEquals(
            $expected,
            $news->extractFromJson($json),
        );
    }

    public function providerExtractFromJson(): Generator
    {
        yield 'One add' => [
            'allItems' => [
                [
                    'type' => 'ad',
                    'title' => 'This is an add',
                    'pubtime' => self::EXAMPLE_UNIX_PUBTIME,
                    'url' => 'url.com',
                    'summary' => 'A summary',
                ],
            ],
            'expected' => [],
        ];

        yield 'One video' => [
            'allItems' => [
                [
                    'type' => 'video',
                    'title' => 'This is a video',
                    'pubtime' => self::EXAMPLE_UNIX_PUBTIME,
                    'url' => 'url.com',
                    'summary' => 'A summary',
                ],
            ],
            'expected' => [],
        ];

        yield 'One article' => [
            'allItems' => [
                [
                    'type' => 'article',
                    'title' => 'The title',
                    'pubtime' => self::EXAMPLE_UNIX_PUBTIME,
                    'url' => 'url.com',
                    'summary' => 'A summary',
                    'publisher' => null,
                    'images' => [],
                ],
            ],
            'expected' => [
                [
                    'title' => 'The title',
                    'datetime' => self::EXAMPLE_FORMATTED_DATETIME,
                    'url' => 'url.com',
                    'summary' => 'A summary',
                    'timezone' => self::EXAMPLE_TIMEZONE,
                    'source' => 'FinanceYahoo',
                    'publisher' => null,
                    'images' => [],
                ],
            ],
        ];

        yield 'A mix with add, video and articles' => [
            'allItems' => [
                [
                    'type' => '-',
                    'title' => 'Unknown type title',
                    'pubtime' => self::EXAMPLE_UNIX_PUBTIME,
                    'url' => 'url.~0~.com',
                    'summary' => 'summary',
                ],
                [
                    'type' => 'ad',
                    'title' => 'This is an Add!',
                    'pubtime' => self::EXAMPLE_UNIX_PUBTIME,
                    'url' => 'url.~1~.com',
                    'summary' => 'summary',
                ],
                [
                    'type' => 'article',
                    'title' => 'The first title',
                    'pubtime' => self::EXAMPLE_UNIX_PUBTIME,
                    'url' => 'url.1.com',
                    'summary' => 'First summary',
                    'source' => 'FinanceYahoo',
                    'publisher' => null,
                    'images' => [],
                ],
                [
                    'type' => 'video',
                    'title' => 'This is another Add!',
                    'pubtime' => self::EXAMPLE_UNIX_PUBTIME,
                    'url' => 'url.~2~.com',
                    'summary' => 'summary',
                ],
                [
                    'type' => 'article',
                    'title' => 'The second title',
                    'pubtime' => self::EXAMPLE_UNIX_PUBTIME,
                    'url' => 'url.2.com',
                    'summary' => 'Second summary',
                    'source' => 'FinanceYahoo',
                    'publisher' => null,
                    'images' => [],
                ],
            ],
            'expected' => [
                [
                    'title' => 'The first title',
                    'datetime' => self::EXAMPLE_FORMATTED_DATETIME,
                    'url' => 'url.1.com',
                    'summary' => 'First summary',
                    'timezone' => self::EXAMPLE_TIMEZONE,
                    'source' => 'FinanceYahoo',
                    'publisher' => null,
                    'images' => [],
                ],
                [
                    'title' => 'The second title',
                    'datetime' => self::EXAMPLE_FORMATTED_DATETIME,
                    'url' => 'url.2.com',
                    'summary' => 'Second summary',
                    'timezone' => self::EXAMPLE_TIMEZONE,
                    'source' => 'FinanceYahoo',
                    'publisher' => null,
                    'images' => [],
                ],
            ],
        ];
    }

    private function createJsonWithItems(array $allItems): array
    {
        $streamStore = [
            'streams' => [
                [
                    'data' => [
                        'stream_items' => $allItems,
                    ],
                ],
            ],
        ];

        return [
            'context' => [
                'dispatcher' => [
                    'stores' => [
                        'StreamStore' => $streamStore,
                    ],
                ],
            ],
        ];
    }
}
