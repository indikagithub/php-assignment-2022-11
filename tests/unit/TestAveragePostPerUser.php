<?php
declare(strict_types=1);

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Statistics\Calculator\AveragePostPerUser;
use Statistics\Dto\StatisticsTo;

/**
 * Class TestAveragePostPerUser
 *
 * @package Tests\unit
 */
class TestAveragePostPerUser extends TestCase
{
    /**
     * @test SampleDataFile exist
     */
    public function testSampleDataFile(): void
    {
        $filePath = dirname(__DIR__) . '/data/' . 'social-posts-response.json';
        $this->assertFileExists($filePath);
    }

    /**
     * @test
     */
    public function testPostPerUser(): void
    {
        $perUserPost = new AveragePostPerUser();
        $jsonRequestData = $this->readJson('social-posts-response.json');
        $postData = !empty($jsonRequestData['data']['posts']) ? $jsonRequestData['data']['posts'] : [];

        foreach ($postData as $post) {
            $perUserPost->postCount++;
            $perUserPost->totalUserCount[] = $post['from_id'] ?? null;
        }

        $averageAccount = $this->getMethod(AveragePostPerUser::class, 'doCalculate');
        $averageAccount->setAccessible(true);
        $this->assertEquals(2, $averageAccount->invoke($perUserPost)->getValue());
        //total posts 8 and 4 unique users
    }

    private function readJson($filename)
    {
        $content = file_get_contents(dirname(__DIR__) . '/data/' . $filename);
        return json_decode($content, true);
    }

    private function getMethod($class, $name)
    {
        $method = new ReflectionMethod($class, $name);
        $method->setAccessible(true);

        return $method;
    }
}