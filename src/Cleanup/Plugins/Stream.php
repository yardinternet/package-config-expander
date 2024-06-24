<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Cleanup\Plugins;

class Stream extends BaseAbstract
{
    protected string $plugin = 'stream/stream.php';

    public function cleanup(): void
    {
        if (! $this->pluginIsActive()) {
            return;
        }

        if ($this->pluginIsNetworkActived()) {
            $this->multiSite();

            return;
        }

        $this->singleSite();
    }

    protected function multiSite(): void
    {
        $settings = \get_site_option('wp_stream_network');

        // No settings configured yet.
        if (! is_array($settings)) {
            $exists = false;
            $settings = [];
        }

        $settings = $this->disableKeepRecordsIndefinitely($settings);
        $settings = $this->setRecordsTTL($settings);

        if (isset($exists)) {
            add_network_option(get_current_blog_id(), 'wp_stream_network', $settings);

            return;
        }

        update_network_option(get_current_blog_id(), 'wp_stream_network', $settings);
    }

    protected function singleSite(): void
    {
        $settings = \get_option('wp_stream');

        // No settings configured yet.
        if (! is_array($settings)) {
            $settings = [];
        }

        $settings = $this->disableKeepRecordsIndefinitely($settings);
        $settings = $this->setRecordsTTL($settings);

        update_option('wp_stream', $settings);
    }

    /**
     * @param array<string, mixed> $settings
     *
     * @return array<string, mixed> $settings
     */
    protected function disableKeepRecordsIndefinitely(array $settings): array
    {
        $settings['general_keep_records_indefinitely'] = 0;

        return $settings;
    }

    /**
     * Without this setting the cron-job, which is used to delete old records, returns early.
     *
     * @param array<string, mixed> $settings
     *
     * @return array<string, mixed> $settings
     */
    protected function setRecordsTTL(array $settings): array
    {
        if (! empty($settings['general_records_ttl'])) {
            return $settings;
        }

        $settings['general_records_ttl'] = 30;

        return $settings;
    }
}
