<?php
namespace Findus23;
require_once 'functions.php';

class FunctionTest extends \PHPUnit_Framework_TestCase {
	public function testslugify() {
		$titles = array (
				"Test" => "test",
				"Überschrift" => "ueberschrift",
				"Leer Zeichen" => "leer-zeichen",
				"Fuß" => "fuss",
				"Sonderzeichen?=%" => "sonderzeichen",
				"Rosé" => "rose",
				"Weinbrief Nr. 18" => "weinbrief-nr-18"
		);
		foreach ($titles as $title => $slug) {
			$this->assertEquals(slugify($title), $slug);
		}
	}
	public function testformat_time() {
		$this->assertEquals("17. Oktober 2010", format_date(strtotime('2010-10-17 00:00:00'),"de"));
		$this->assertEquals("5. Jänner 2015", format_date(strtotime('2015-01-5 00:00:00'),"de"));
		$this->assertEquals("30. Dezember 2016", format_date(strtotime('2016-12-30 00:00:00'),"de"));
		$this->assertEquals("17. October 2010", format_date(strtotime('2010-10-17 00:00:00'),"en"));
		
		;
	}
}
