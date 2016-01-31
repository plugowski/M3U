<?php
use plugowski\m3u\M3U;
use plugowski\m3u\M3UEntity;

class M3UTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCorrectlyAddNewRecords()
    {
        $m3u = new M3U();
        $m3u->addChannel(new M3UEntity('TVP 1', 'rtmp://test1', 'TVP_1.png', 'Popularne'));
        $m3u->addChannel(new M3UEntity('TVP 2', 'rtmp://test2', 'TVP_2.png', 'Rozrywka'));

        $this->assertEquals(2 ,count($m3u));
    }

    /**
     * @test
     */
    public function shouldGenerateCorrectM3uList()
    {
        $m3u = new M3U();
        $m3u->addChannel(new M3UEntity('TVP 1', 'rtmp://test1', 'TVP_1.png', 'Popularne'));

        $list  = '#EXTM3U' . PHP_EOL . PHP_EOL;
        $list .= '#EXTINF:-1 tvg-id="TVP 1" tvg-logo="TVP_1.png" group-title="Popularne",TVP 1' . PHP_EOL;
        $list .= 'rtmp://test1' . PHP_EOL . PHP_EOL;
        $list .= '#EXT-X-ENDLIST';

        $this->assertEquals($list , $m3u->generate());
    }

    /**
     * @test
     */
    public function shouldCorrectlyImportM3uFileIntoClass()
    {
        $m3u = new M3U();
        $m3u->import(__DIR__ . '/test_list.m3u');

        $this->assertEquals(3, count($m3u));
        $this->assertEquals('TVP 1', $m3u->current()->getName());

        $m3u->next();
        $this->assertEquals('rtmp://test/tvp2', $m3u->current()->getStreamUrl());
    }

    /**
     * @test
     */
    public function shouldFindChannelInPlaylist()
    {
        $m3u = new M3U();
        $m3u->import(__DIR__ . '/test_list.m3u');
        $channel = $m3u->findChannel('TVP 2');

        $this->assertEquals('TVP 2', $channel->getName());
        $this->assertNull($m3u->findChannel('Polsat'));
    }

    /**
     * @test
     */
    public function shouldThrowBadFileException()
    {
        $this->setExpectedException('Exception');

        $m3u = new M3U();
        $m3u->import('wrong_file.m3u');
    }
}