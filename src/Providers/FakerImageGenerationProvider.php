<?php

namespace Swatty007\FakerImageGenerator\Providers;

use Faker\Generator;
use Faker\Provider\Base;

class FakerImageGenerationProvider extends Base
{
    public string $directory;
    public bool $fullPath;

    public int $width;
    public int $height;
    public string $format;

    public $backgroundColor;

    public $text;
    public $textColor;
    public $fontFace;

    public function __construct(
        Generator $generator,
        $directory = null,
        $width = 640,
        $height = 480,
        $format = 'png',
        $fullPath = true,
        $text = true,
        $backgroundColor = null,
        $textColor = null,
        $fontFace = null
    ) {
        // Allow our users to define a configuration for our entire instance.
        // Or fallback to our default config values
        $this->directory = $directory ?? config('faker-image-generator.directory');
        $this->width = $width ?? config('faker-image-generator.width');
        $this->height = $height ?? config('faker-image-generator.height');
        $this->format = $format ?? config('faker-image-generator.format');
        $this->fullPath = $fullPath ?? config('faker-image-generator.full_path');
        $this->text = $text ?? config('faker-image-generator.text');
        $this->backgroundColor = $backgroundColor ?? config('faker-image-generator.background_color');
        $this->textColor = $textColor ?? config('faker-image-generator.text_color');
        $this->fontFace = $fontFace ?? config('faker-image-generator.font_face');

        parent::__construct($generator);
    }

    /**
     * Generate a new image to disk and return its location
     *
     * Requires gd (default in most PHP setup).
     *
     * @param null $directory
     * @param null $width
     * @param null $height
     * @param null $format
     * @param null $fullPath
     * @param null $text
     * @param null $backgroundColor
     * @param null $textColor
     * @param null $fontFace
     * @return false|\RuntimeException|string
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public function imageGenerator(
        $directory = null,
        $width = null,
        $height = null,
        $format = null,
        $fullPath = null,
        $text = null,
        $backgroundColor = null,
        $textColor = null,
        $fontFace = null
    ) {
        // Allow our users to overwrite our settings per item, if they desire so
        $directory = $directory ?? $this->directory;
        $width = $width ?? $this->width;
        $height = $height ?? $this->height;
        $format = $format ?? $this->format;
        $fullPath = $fullPath ?? $this->fullPath;
        $text = $text ?? $this->text;
        $backgroundColor = $backgroundColor ?? $this->backgroundColor;
        $textColor = $textColor ?? $this->textColor;
        $fontFace = $fontFace ?? $this->fontFace;

        // Validate directory path
        if (! is_dir($directory) || ! is_writable($directory)) {
            throw new \InvalidArgumentException(sprintf('Cannot write to directory "%s"', $directory));
        }

        // Generate a random filename. Use the server address so that a file
        // generated at the same time on a different server won't have a collision.
        $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $filename = $name . '.' . $format;
        $filepath = $directory . DIRECTORY_SEPARATOR . $filename;

        // Create our Image, if our driver exists
        if (function_exists('imagecreate')) {
            $image = imagecreate($width, $height);

            if ($backgroundColor) {
                if (substr($backgroundColor, 0, 1) == '#') {
                    $rgb = str_split(substr($backgroundColor, 1), 2);
                } else {
                    $rgb = str_split($backgroundColor, 2);
                }
                imagecolorallocate($image, hexdec($rgb[0]), hexdec($rgb[1]), hexdec($rgb[2]));
            } else {
                imagecolorallocate($image, 0, 0, 0);
            }

            if ($text === true) {
                $text = $width . 'x' . $height;
            }

            if (! is_null($text)) {
                if ($textColor) {
                    if (substr($textColor, 0, 1) == '#') {
                        $rgb = str_split(substr($textColor, 1), 2);
                    } else {
                        $rgb = str_split($textColor, 2);
                    }
                    $text_color = imagecolorallocate($image, hexdec($rgb[0]), hexdec($rgb[1]), hexdec($rgb[2]));
                } else {
                    $text_color = imagecolorallocate($image, 255, 255, 255);
                }

                $fontSize = 200;
                $textBoundingBox = imagettfbbox($fontSize, 0, $fontFace, $text);
                // decrease the default fonts size until it fits nicely within the image - Code adapted from https://github.com/img-src/placeholder
                while (((($width - ($textBoundingBox[2] - $textBoundingBox[0])) < 10) || (($height - ($textBoundingBox[1] - $textBoundingBox[7])) < 10)) && ($fontSize > 1)) {
                    $fontSize --;
                    $textBoundingBox = imagettfbbox($fontSize, 0, $fontFace, $text);
                }
                imagettftext($image, $fontSize, 0, ($width / 2) - (($textBoundingBox[2] - $textBoundingBox[0]) / 2), ($height / 2) + (($textBoundingBox[1] - $textBoundingBox[7]) / 2), $text_color, $fontFace, $text);
            }

            switch (strtolower($format)) {
                case 'jpg':
                case 'jpeg':
                default:
                    $success = imagejpeg($image, $filepath);
                    break;
                case 'png':
                    $success = imagepng($image, $filepath);
            }

            $success = imagedestroy($image);
        } else {
            // @codeCoverageIgnoreStart
            return new \RuntimeException('GD is not available on this PHP installation. Impossible to generate image.');
            // @codeCoverageIgnoreEnd
        }

        if (! $success) {
            // @codeCoverageIgnoreStart
            // could not save the file - fail silently.
            return false;
            // @codeCoverageIgnoreEnd
        }

        return $fullPath ? $filepath : $filename;
    }
}
