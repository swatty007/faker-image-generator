<?php
namespace Swatty007\FakerImageGenerator\Tests;

use Faker\Factory;
use Orchestra\Testbench\TestCase;
use Swatty007\FakerImageGenerator\FakerImageGeneratorServiceProvider;
use Swatty007\FakerImageGenerator\Providers\FakerImageGenerationProvider;

class ImagesGeneratorProviderTest extends TestCase
{
    private \Faker\Generator $faker;
    private array $files;

    protected function getPackageProviders($app)
    {
        return [FakerImageGeneratorServiceProvider::class];
    }

    /**
     * Register our custom faker provider
     *
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->faker = Factory::create();

        $imageGenerator = new FakerImageGenerationProvider($this->faker);
        $imageGenerator->fontFace = __dir__ . '/../resources/assets/fonts/Roboto-Regular.ttf';

        $this->faker->addProvider($imageGenerator);

        $this->files = [];
    }

    /**
     * Clean up any temporary images again
     *
     */
    public function tearDown() : void
    {
        if ($this->files !== null) {
            foreach ($this->files as $f) {
                @unlink($f);
            }
        }

        parent::tearDown();
    }

    /**
     * Helper Function to validate if our images are valid
     *
     * @param $test
     */
    private function _testImage($test)
    {
        $this->assertNotNull(@exif_imagetype($test));
    }

    /**
     * Test creating an image with the default setup
     *
     * @return void
     */
    public function testCreateDefaultImage()
    {
        $this->files[] = $test = $this->faker->imageGenerator();
        $this->_testImage($test);
    }

    /**
     * Test using a invalid directory, /dev/null
     *
     * @return void
     */
    public function testInvalidDirectory()
    {
        try {
            $this->faker->imageGenerator('/dev/null');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Cannot write to directory "/dev/null"');
        }
    }

    /**
     * Test using text using a colour without a hex
     *
     * @return void
     */
    public function testTextColorHex()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'png', true, null, '#0000ff');
        $this->_testImage($test);
    }

    /**
     * Test using text using a colour with a hex
     *
     * @return void
     */
    public function testTextColorHexNoHash()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'png', true, null, '0000ff');
        $this->_testImage($test);
    }

    /**
     * Test using a background colour with a hex
     *
     * @return void
     */
    public function testBackgroundColorHex()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'png', true, 'ImagesGenerator', null, '#0000ff');
        $this->_testImage($test);
    }

    /**
     * Test using a background colour without a hex
     *
     * @return void
     */
    public function testBackgroundColorHexNoHash()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'png', true, 'ImagesGenerator', null, '0000ff');
        $this->_testImage($test);
    }

    /**
     * Test showing text over the image
     *
     * @return void
     */
    public function testText()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'png', true, 'ImagesGenerator');
        $this->_testImage($test);
    }

    /**
     * Test using the width and height as the text
     *
     * @return void
     */
    public function testTextWidthHeight()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'png', true, true);
        $this->_testImage($test);
    }

    /**
     * Test creating a image with an extention of .jpg
     *
     * @return void
     */
    public function testJPG()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'jpg');
        $this->_testImage($test);
    }

    /**
     * Test creating a image with an extension of .jpeg
     *
     * @return void
     */
    public function testJPEG()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'jpeg');
        $this->_testImage($test);
    }

    /**
     * Test creating a image with an extention of .png
     *
     * @return void
     */
    public function testPNG()
    {
        $this->files[] = $test = $this->faker->imageGenerator(null, 640, 480, 'png');
        $this->_testImage($test);
    }

    /**
     * Test if our readmes default image example works
     *
     * @return void
     */
    public function test_readme_default_image()
    {
        $this->files[] = $test = $this->faker->imageGenerator();
        $this->_testImage($test);
    }

    /**
     * Test if our readmes custom image example works
     *
     * @return void
     */
    public function test_readme_custom_image()
    {
        $this->files[] = $test = $this->faker->imageGenerator('docs', 640, 480, 'png', true, 'Faker', '#0018ff', '#ffd800');
        $this->_testImage($test);
    }
}
