<?php

require_once "../autoload.php";
require_once "../lib/minify/CSSmin.php";
require_once "mocks.php";

use Pbg\Wordpress\Plugin\Minify as Minify;

class StyleTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $args = array(
            "extra" => array("after" => "sample content"),
            "src" => "http://example.com"
        );
        $this->inline = new Minify\Style($args);

        $args = array(
            "src" => "http://google.com/whatever-path"
        );

        $this->remote = new Minify\Style($args);
    }

    public function testIsInline()
    {
        $this->assertFalse($this->remote->isInline());
        $this->assertTrue($this->inline->isInline());
    }

    public function testIsRemote()
    {
        $this->assertFalse($this->inline->isRemote());
        $this->assertTrue($this->remote->isRemote());
    }

    public function testGetFilePathExceptions()
    {
        // throws exception for inline and remote styles
        $this->setExpectedException('InvalidArgumentException');
        $this->inline->getFilePath();
        $this->setExpectedException('InvalidArgumentException');
        $this->remote->getFilePath();

        $this->setExpectedException('InvalidArgumentException');

        // test for not WP_CONTENT_DIR nor wp-includes
        $args = array(
            "src" => "http://example.com/dummy/style.css"
        );
        $style = new Minify\Style($args);
        $style->getFilePath();
    }

    public function testGetFilePath()
    {
        // test for WP_CONTENT_DIR path
        $args = array(
            "src" => "http://example.com/wp-content/style.css"
        );
        $style = new Minify\Style($args);

        $this->assertEquals($style->getFilePath(), "/home/piotr/wordpress/wp-content/style.css");

        // test for wp-includes path
        $args = array(
            "src" => "http://example.com/wp-includes/style.css"
        );
        $style = new Minify\Style($args);

        $this->assertEquals($style->getFilePath(), "/home/piotr/wordpress/wp-includes/style.css");

    }

    public function testGetStyleContent()
    {
        $this->assertEquals(
            $this->inline->getStyleContent(),
            "sample content"
        );
        // how to do remote & file?
        $mock = new TestableStyle(array("src" => "http://google.com"));
        $this->assertEquals(
            $mock->getStyleContent(),
            "remote content"
        );

        $mock = new TestableStyle(array("src" => "http://example.com"));
        $this->assertEquals(
            $mock->getStyleContent(),
            "file content"
        );
    }

    public function testMinify()
    {
        $mock = new TestableStyle(array("src" => "http://example.com"));
        $this->assertEquals(
            "minified content",
            $mock->minify()
        );
    }
}
