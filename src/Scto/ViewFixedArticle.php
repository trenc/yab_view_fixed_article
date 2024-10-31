<?php

declare(strict_types=1);

class ViewFixedArticle
{
    public function __construct()
    {
        register_callback([$this, 'injectJS'], 'admin_side', 'body_end');
    }

    public function injectJS(string $evt, string $stp): void
    {
        global $event, $prefs;

        if ($event !== 'article') {
            return;
        }

        echo '<script>' . $this->getJavaScript($prefs['permlink_mode'], hu) . '</script>';
    }

    protected function getJavaScript(string $permlink_mode, string $site_url): string
    {
        $JS = <<<JS
(function() {
    document.body.addEventListener('click', (event) => {

        const viewLinkHtmlId = 'article_partial_article_view';
        const viewLink = document.querySelector('#' + viewLinkHtmlId);

        if (event.target.id !== viewLinkHtmlId) {
            return;
        }

        const articleStatus = document.querySelector('#status').value;
        const articleSection = document.querySelector('#section').value;
        const permlinkMode = '$permlink_mode';
        const siteUrl = '$site_url';

        if (articleStatus !== '5') {
            return;
        }

        const link = permlinkMode === 'messy'
            ? siteUrl + '?s=' + articleSection
            : siteUrl + articleSection + '/';

        event.preventDefault();
        window.open(link);
    });
})();
JS;

        return $JS;
    }
}
