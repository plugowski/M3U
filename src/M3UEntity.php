<?php
namespace plugowski\m3u;

/**
 * Class M3UEntity
 * @package plugowski\m3u
 */
class M3UEntity
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $streamUrl;
    /**
     * @var string
     */
    private $logo;
    /**
     * @var string
     */
    private $group;

    /**
     * M3UEntity constructor.
     * @param string $name
     * @param string $streamUrl
     * @param string $logo
     * @param string $group
     */
    public function __construct($name, $streamUrl, $logo, $group)
    {
        $this->name = $name;
        $this->streamUrl = $streamUrl;
        $this->logo = $logo;
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStreamUrl()
    {
        return $this->streamUrl;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return  '#EXTINF:-1 ' .
                'tvg-id="' . $this->getName() . '" ' .
                'tvg-logo="' . $this->getLogo() . '" ' .
                'group-title="' . $this->getGroup() . '",' . $this->getName() . PHP_EOL .
                $this->getStreamUrl();
    }
}