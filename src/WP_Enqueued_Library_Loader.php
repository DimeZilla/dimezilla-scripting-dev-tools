<?php

namespace Dimezilla_Scripting_Dev_tools;

class WP_Enqueued_Library_Loader {

    protected $cacheHandles = [];

    public function getRegisteredData($wp_object, $handle = '')
    {
        return $wp_object->registered[$handle];
    }

    public function printDataLoadStatement($setter, $handle = '', $data)
    {
        ?>
            window.WP_ENQUEUED.<?php echo $setter; ?>("<?php echo $handle; ?>", <?php echo json_encode($data); ?>);
        <?php
    }

    protected function addCache($handle) {
        if (array_search($handle, $this->cacheHandles) === false) {
            $this->cacheHandles[] = $handle;
        }
    }

    protected function getDependencies($wp_object, $handle = '')
    {
        $data = $this->getRegisteredData($wp_object, $handle);
        return $data->deps ?? [];
    }

    protected function cacheDeps($wp_object, $deps = []) {
        if (empty($deps)) {
            return;
        }

        $this->walkQueue($wp_object, $deps);
    }

    protected function walkQueue($wp_object, $queue = [])
    {
        foreach ($queue as  $handle) {
            $this->addCache($handle);
            $unCachedDependencies = array_diff(
                $this->getDependencies($wp_object, $handle),
                $this->cacheHandles
            );
            $this->cacheDeps($wp_object, $unCachedDependencies);
        }
    }

    public function printLoadScript($wp_object, $setter)
    {
        // reset the cache
        $this->cacheHandles = [];

        // fill up our cache with all of our handles,
        // their dependencieis, and their dependencies' dependencies
        $this->walkQueue($wp_object, $wp_object->queue);
        foreach ($this->cacheHandles as $handle) {
            $data = $this->getRegisteredData($wp_object, $handle);
            $this->printDataLoadStatement($setter, $handle, $data);
        }
    }

    public function printLoadScripts()
    {
        global $wp_scripts;
        global $wp_styles;
        ?>
        <script type="text/javascript" id="dsdt-wp-enqueue-library">
        if (typeof window.WP_ENQUEUED !== 'undefined') {
            <?php $this->printLoadScript($wp_scripts, 'setScript'); ?>
            <?php $this->printLoadScript($wp_styles, 'setStyle'); ?>
        }
        </script>
        <?php
    }
}
