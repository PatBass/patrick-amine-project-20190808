<?php

namespace App\Tests\FunctionalTests\Front\Zonage123;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class Zonage123Test extends WebTestCase
{
    /**
     * @var WebDriver\Remote\RemoteWebDriver
     */
    private $webDriver;
	
    /**
     * get Parameters from .env file
     */	
	private function getEnvParamValue($param) {
		if (!isset($_SERVER['APP_ENV'])) {
			if (!class_exists(Dotenv::class)) {
				throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
			}
			(new Dotenv(true))->load(__DIR__.'/../../../../.env');
		}	
		if (isset($_ENV[$param]))		
			return $_ENV[$param];
		else
			throw new \RuntimeException("$param environment variable is not defined .");	
	}

    /**
     * init webdriver
     */
    public function setUp() {
		
		$urlSelenuim = $this->getEnvParamValue('SELENIUM_URL');
		
        $options = new ChromeOptions();
        $options->addArguments(['--disable-gpu', '--window-size=1200,1100', '--no-sandbox']);

        $desiredCapabilities = WebDriver\Remote\DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability('trustAllSSLCertificates', true);

        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->webDriver = WebDriver\Remote\RemoteWebDriver::create(
                        $urlSelenuim,
                        $desiredCapabilities
        );
    }


    /**
     * Method testZonage123
     * @test
     */
    public function testZonage123()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/zones/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/zones/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur du zonage de votre commune : 1, 1 bis, 2 ou 3 - Zonage - service-public.fr")
		);			
		
        // click | id=geoAPILocalitiescommuneInsee | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommuneInsee"))->click();
        // type | id=geoAPILocalitiescommuneInsee | Langoiran (33550)
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommuneInsee"))->sendKeys("Langoiran (33550)");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('geoAPILocalitiescommuneInsee-suggestions-suggestion-1'))
		);					
        // click | id=geoAPILocalitiescommuneInsee-suggestions-suggestion-1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommuneInsee-suggestions-suggestion-1"))->click();
		
		
        // click | id=geoAPILocalitiescommuneInsee-validate-button | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommuneInsee-validate-button"))->click();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommuneInsee-validate-button"))->click();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommuneInsee-validate-button"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Valider'])[1]/following::p[1]"))
		);			
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Valider'])[1]/following::p[1] | Langoiran se situe en zone 3
        $this->assertEquals("Langoiran se situe en zone 3", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Valider'])[1]/following::p[1]"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
