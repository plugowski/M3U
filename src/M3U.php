<?php
namespace plugowski\m3u;

/**
 * Class M3U
 * @package plugowski\m3u
 */
class M3U extends M3UIterator
{
    /**
     * Adding new record to m3u collection
     * @param M3UEntity $entity
     */
    public function addChannel(M3UEntity $entity)
    {
        $this->records[] = $entity;
    }

    /**
     * @param string $name
     * @return M3UEntity
     */
    public function findChannel($name)
    {
        foreach ($this->records as $record) {
            if ($name === $record->getName()) {
                return $record;
            }
        }
        return null;
    }

    /**
     * Import existing m3u playlist file into the class
     * @param string $filename
     * @throws \Exception
     */
    public function import($filename)
    {
        if (!file_exists($filename)) {
            throw new \Exception('File doesn\'t exists!');
        }

        $fh = fopen($filename, 'r');
        $content = fread($fh, filesize($filename));
        fclose($fh);

        $this->parseString($content);
    }

    /**
     * Parsing string and convert it into objects
     * @param string $string
     */
    private function parseString($string)
    {
        $data = explode(PHP_EOL, $string);
        foreach ($data as $index => $row) {
            $vars = [];
            if (preg_match("/EXTINF:([-0-9]+)(.*)/i", $row, $parsed)) {
                $ex = explode('=', trim($parsed[2]));
                for ($i = 0; $i < count($ex); $i++) {
                    if (isset($ex[$i + 1]) && preg_match('/^"(.*)"\s*([^,]*)/', $ex[$i + 1], $v)) {
                        $vars[$ex[$i]] = $v[1];
                        $ex[$i + 1] = str_replace('"' . $v[1] . '" ', '', $v[0]);
                    }
                }
                $stream = explode(' ', $data[$index + 1]);
                $this->records[] = new M3UEntity($vars['tvg-id'], $stream[0], $vars['tvg-logo'], $vars['group-title']);
            }
        }
    }

    /**
     * Generate string output with m3u playlist
     * @return string
     */
    public function generate()
    {
        $m3u = '#EXTM3U' . PHP_EOL . PHP_EOL;
        foreach ($this->records as $record) {
            $m3u .= $record->toString();
            $m3u .= PHP_EOL . PHP_EOL;
        }
        $m3u .= '#EXT-X-ENDLIST';
        return $m3u;
    }
}