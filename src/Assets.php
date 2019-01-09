<?php
/**
 * This reads our manifest and returns the path to the file
 */
namespace Dimezilla_Scripting_Dev_tools;

class Assets
{
    protected $manifestName = "manifest.json";

    /**
     * Storage for our manifest object
     * @var array
     */
    protected $manifest;

    protected static $instance;

    public function __construct()
    {
        $manifestPath = join(DIRECTORY_SEPARATOR, [dsdt_root_dir(), 'dist', $this->manifestName]);
        $this->manifest = [];
        if (file_exists($manifestPath)) {
            $this->manifest = json_decode(file_get_contents($manifestPath), true);
        }
    }

    public function manifestValue($handle = '')
    {
        if (isset($this->manifest[$handle])) {
            return $this->manifest[$handle];
        }

        return null;
    }

    public function _assetUri($handle = '')
    {
        $asset = $this->manifestValue($handle);
        if (!empty($asset)){
            return dsdt_plugin_dir_url() . '/dist/' . $asset;
        }

        return "";
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function __callStatic($method, $arguments = [])
    {
        $instance = self::getInstance();
        $method_name = '_' . $method;
        return call_user_func_array([$instance, $method_name], $arguments);
    }
}
