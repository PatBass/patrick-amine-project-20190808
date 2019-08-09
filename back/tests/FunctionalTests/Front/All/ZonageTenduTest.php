<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;


class ZonageTenduTest extends WebTestCase
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
        $options->addArguments(['--disable-gpu', '--window-size=1200,4000', '--no-sandbox']);

        $desiredCapabilities = WebDriver\Remote\DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability('trustAllSSLCertificates', true);

        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->webDriver = WebDriver\Remote\RemoteWebDriver::create(
                        $urlSelenuim,
                        $desiredCapabilities
        );
    }

    /**
     * Method testZonageTendu
     * @test
     */
    public function testZonageTendu()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/zones-tendues/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/zones-tendues/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Savoir si un logement est situé en zone tendue - Zonage - service-public.fr")
		);	

		
        // click | id=geoAPILocalitiescommune | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune"))->click();

		
        // type | id=geoAPILocalitiescommune | Langogne (48300)
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune"))->sendKeys("Langogne (48300)");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('geoAPILocalitiescommune-suggestions-suggestion-1'))
		);				
        // click | id=geoAPILocalitiescommune-suggestions-suggestion-1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-suggestions-suggestion-1"))->click();
		
		
        // click | id=geoAPILocalitiescommune-validate-button | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-validate-button"))->click();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-validate-button"))->click();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-validate-button"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Valider'])[1]/following::p[1]"))
		);		

        // assertElementPresent | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Valider'])[1]/following::p[1] | Langogne n'est pas en zone tendue
       // $this->assertTrue($this->webDriver->findElements(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Valider'])[1]/following::p[1]")));
        $this->assertEquals("Langogne n'est pas en zone tendue", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Valider'])[1]/following::p[1]"))->getText());
		
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}