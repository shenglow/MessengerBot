<?php
/**
 * Facebook bot messenger
 *
 * @version 1.0.0
 * @author Boon
 * @since 1.0.0 2019/03/20 Boon: First release
 */

/**
 * Class FacebookBot
 */
class FacebookBot {
    /**
     * @var array $config Setting's array
     */
    private $config;

    /**
     * FacebookBot constructor
     *
     * @return self
     */
    public function __construct()
    {
        $this->config = include('settings/settings.ini.php');
    }

    /**
     * get provided $section setting in config array
     *
     * @param string $section Section in the config array
     * @param string $key Key in the section
     * @param bool $throwException Use for decide when setting is not set want to throw Exception or not
     *
     * @return mixed Return setting's value
     */
    public function getSetting($section, $key, $throwException = true)
    {
        if (isset($this->config[$section][$key])) {
            return $this->config[$section][$key];
        } else {
            if ($throwException === true) {
                throw new Exception('Setting with section {'.$section.'} value {'.$key.'}');
            } else {
                return false;
            }
        }
    }

    /**
     * set provided $section setting in config array
     *
     * @param string $section Section in the config array
     * @param string $key Key in the section
     * @param mixed $value The value want to set
     *
     * @return void
     */
    public function setSetting($section, $key, $value)
    {
        $this->config[$section][$key] = $value;
    }

    /**
     * check $section has setting
     *
     * @param string $section Section in the config array
     * @param string $key Key in the section
     *
     * @return bool 
     */
    public function hasSetting($section, $key)
    {
        return isset($this->config[$section][$key]);
    }
}
?>