<?php

namespace PHPMaker2023\gestion_ECOLE;

/**
 * CAPTCHA class
 */
class PhpCaptcha extends CaptchaBase
{
    public static $BackgroundColor = "FFFFFF"; // Hex string
    public static $TextColor = "003359"; // Hex string
    public static $NoiseColor = "64A0C8"; // Hex string
    public static $Width = 250;
    public static $Height = 50;
    public static $Characters = 6;
    public static $FontSize = 0;
    public static $ImageType = IMG_PNG;
    public static $Font = "aftershock";
    public $Response = "";
    public $ResponseField = "captcha";

    /**
     * Constructor
     *
     * @param string $Font Font file name
     */
    public function __construct()
    {
        if (self::$FontSize <= 0) {
            self::$FontSize = $this->getHeight() * 0.55;
        }
    }

    /**
     * Generate code
     *
     * @param int $Characters Number of characters
     * @return string
     */
    protected function generateCode($Characters)
    {
        $possible = "23456789BCDFGHJKMNPQRSTVWXYZ"; // Possible characters
        $code = "";
        $i = 0;
        while ($i < $Characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            $i++;
        }
        return $code;
    }

    /**
     * Convert hex to RGB
     *
     * @param string $hexstr Hex string
     * @return array Array of RGB
     */
    protected function hexToRGB($hexstr)
    {
        $int = hexdec($hexstr);
        return [
            "R" => 0xFF & ($int >> 0x10),
            "G" => 0xFF & ($int >> 0x8),
            "B" => 0xFF & $int
        ];
    }

    /**
     * Output image
     *
     * @return string Code
     */
    public function show()
    {
        $code = $this->generateCode(self::$Characters);
        $oriCode = $code;
        $code = "";
        $len = strlen($oriCode);
        for ($i = 0; $i < $len; $i++) {
            $code .= $oriCode[$i];
            if ($i < $len - 1) {
                $code .= " ";
            }
        }
        $code = trim($code);
        try {
            $image = imagecreate($this->getWidth(), $this->getHeight());
        } catch (\Exception $e) {
            throw new \Exception("PhpCaptcha: Cannot initialize new GD image stream - " . $e->getMessage());
        }
        $rgb = $this->hexToRGB(self::$BackgroundColor);
        $backgroundColor = imagecolorallocate($image, $rgb["R"], $rgb["G"], $rgb["B"]);
        $rgb = $this->hexToRGB(self::$TextColor);
        $textColor = imagecolorallocate($image, $rgb["R"], $rgb["G"], $rgb["B"]);
        $rgb = $this->hexToRGB(self::$NoiseColor);
        $noiseColor = imagecolorallocate($image, $rgb["R"], $rgb["G"], $rgb["B"]);
        // Generate random dots in background
        for ($i = 0; $i < ($this->getWidth() * $this->getHeight()) / 3; $i++) {
            imagefilledellipse($image, mt_rand(0, $this->getWidth()), mt_rand(0, $this->getHeight()), 1, 1, $noiseColor);
        }
        // Generate random lines in background
        for ($i = 0; $i < ($this->getWidth() * $this->getHeight()) / 150; $i++) {
            imageline($image, mt_rand(0, $this->getWidth()), mt_rand(0, $this->getHeight()), mt_rand(0, $this->getWidth()), mt_rand(0, $this->getHeight()), $noiseColor);
        }
        $fontFile = self::$Font;
        // Always use full path
        if (!ContainsString($fontFile, ".")) {
            $fontFile .= ".ttf";
        }
        $fontFile = IncludeTrailingDelimiter(Config("FONT_PATH"), true) . $fontFile;
        // Create textbox and add text
        try {
            $textBox = imagettfbbox(self::$FontSize, 0, $fontFile, $code);
        } catch (\Exception $e) {
            throw new \Exception("PhpCaptcha: Error in imagettfbbox function - " . $e->getMessage());
        }
        $x = ($this->getWidth() - $textBox[4]) / 2;
        $y = ($this->getHeight() - ($textBox[5] - $textBox[3])) / 2;
        try {
            imagettftext($image, self::$FontSize, 0, intval($x), intval($y), $textColor, $fontFile, $code);
        } catch (\Exception $e) {
            throw new \Exception("PhpCaptcha: Error in imagettfbbox function - " . $e->getMessage());
        }
        // Output captcha image to browser
        if (ob_get_length()) { // Clean buffer
            ob_end_clean();
        }
        ob_start();
        switch (self::$ImageType) {
            case IMG_JPG:
                AddHeader("Content-Type", "image/jpeg");
                imagejpeg($image, null, 90);
                break;
            case IMG_GIF:
                AddHeader("Content-Type", "image/gif");
                imagegif($image);
                break;
            default: // PNG
                AddHeader("Content-Type", "image/png");
                imagepng($image);
                break;
        }
        $data = ob_get_contents();
        ob_end_clean();
        Write($data);
        imagedestroy($image);
        return $oriCode;
    }

    // Width
    public function getWidth()
    {
        return self::$Width;
    }

    // Height
    public function getHeight()
    {
        return self::$Height;
    }

    // HTML tag
    public function getHtml()
    {
        global $Language, $Page;
        $classAttr = ($Page->OffsetColumnClass) ? ' class="' . $Page->OffsetColumnClass . '"' : "";
        $class = $this->getFailureMessage() != "" ? " is-invalid" : "";
        $url = GetUrl("captcha/" . $Page->PageID);
        return <<<EOT
            <div class="row ew-captcha">
                <div{$classAttr}>
                    <p><img src="{$url}" alt="" class="ew-captcha-image" style="width: {$this->getWidth()}; height: {$this->getHeight()};"></p>
                    <input type="text" name="{$this->getElementName()}" id="{$this->getElementId()}" class="form-control ew-form-control{$class}" size="30" placeholder="{$Language->phrase("EnterValidateCode")}">
                    <div class="invalid-feedback">{$this->getFailureMessage()}</div>
                </div>
            </div>
            EOT;
    }

    // HTML tag for confirm page
    public function getConfirmHtml()
    {
        return '<input type="hidden" name="' . $this->getElementName() . '" id="' . $this->getElementId() . '" value="' . HtmlEncode($this->Response) . '">';
    }

    // Validate
    public function validate()
    {
        $sessionName = $this->getSessionName();
        return ($this->Response == Session($sessionName));
    }

    // Client side validation script
    public function getScript()
    {
        return '.addField("' . $this->getElementName() . '", ew.Validators.captcha, ' . ($this->getFailureMessage() != '' ? 'true' : 'false') . ')';
    }
}
