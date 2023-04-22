<?php

namespace PHPMaker2023\gestion_ECOLE;

/**
 * Pager item class
 */
class PagerItem
{
    public $PageSize;
    public $Start;
    public $Text;
    public $Enabled;
    public $ContextClass = "";

    /**
     * Constructor
     *
     * @param int $contextClass Context class
     * @param int $pageSize Page size
     * @param int $start Record number (1-based)
     * @param string $text Text
     * @param bool $enabled Enabled
     * @return void
     */
    public function __construct($contextClass, $pageSize, $start = 1, $text = "", $enabled = false)
    {
        $this->ContextClass = $contextClass;
        $this->PageSize = $pageSize;
        $this->Start = $start;
        $this->Text = $text;
        $this->Enabled = $enabled;
    }

    /**
     * Get page number
     *
     * @return int
     */
    public function getPageNumber(): int
    {
        return ($this->PageSize > 0 && $this->Start > 0) ? ceil($this->Start / $this->PageSize) : 1;
    }

    /**
     * Get URL or query string
     *
     * @param string $url URL without query string
     * @param string $table TableVar
     * @return string
     */
    public function getUrl($url = ""): string
    {
        global $DashboardReport;
        $qs = Config("TABLE_PAGE_NUMBER") . "=" . $this->getPageNumber();
        if ($DashboardReport) {
            $qs .= "&" . Config("PAGE_DASHBOARD") . "=true";
        }
        return $url ? UrlAddQuery($url, $qs) : $qs;
    }

    /**
     * Get "disabled" class
     *
     * @return string
     */
    public function getDisabledClass(): string
    {
        return $this->Enabled ? "" : " disabled";
    }

    /**
     * Get "active" class
     *
     * @return string
     */
    public function getActiveClass(): string
    {
        return $this->Enabled ? "" : " active";
    }

    /**
     * Get attributes
     * - data-ew-action and data-url for normal List pages
     * - data-page for other pages
     *
     * @param string $url URL without query string
     * @param string $action Action (redirect/refresh)
     * @return string
     */
    public function getAttributes($url = "", $action = "redirect"): string
    {
        return 'data-ew-action="' . ($this->Enabled ? $action : "none") . '" data-url="' . $this->getUrl($url) . '" data-page="' . $this->getPageNumber() . '"' .
            ($this->ContextClass ? ' data-context="' . HtmlEncode($this->ContextClass) . '"' : "");
    }
}
