<?php

namespace App\Tests\FunctionalTests\Front\All\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class PensionAlimentaireTest extends WebTestCase
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
			(new Dotenv(true))->load(__DIR__.'/../../../../../.env');
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
        $options->addArguments(['--headless','--disable-gpu', '--window-size=1200,1100', '--no-sandbox']);

        $desiredCapabilities = WebDriver\Remote\DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability('trustAllSSLCertificates', true);

        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->webDriver = WebDriver\Remote\RemoteWebDriver::create(
                        $urlSelenuim,
                        $desiredCapabilities
        );
    }
	

    /**
     * Method testPensionAlimentaire
     * @test
     */
    public function testPensionAlimentaire()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/pension-alimentaire/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/pension-alimentaire/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur de calcul de pension alimentaire - pension alimentaire - service-public.fr")
		);	
		
        // click | id=revenus | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("revenus"))->click();
        // type | id=revenus | 4000
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("revenus"))->sendKeys("4000");
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='*'])[3]/following::span[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='*'])[3]/following::span[1]"))->click();
        // click | id=itemdroitVH-3 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("droitVH_2"))->click();
        // type | id=nbEnfants | 1
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbEnfants"))->sendKeys("1");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('totalPA'))
		);			
        // assertText | id=totalPA | 464,00
        $this->assertEquals("464,00", $this->webDriver->findElement(WebDriver\WebDriverBy::id("totalPA"))->getText());
		
		
        // click | id=nbEnfants | 
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("nbEnfants"))->clear();
        // type | id=nbEnfants | 2
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbEnfants"))->sendKeys("2");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		sleep(2);
        // assertText | id=totalPA | 792,00
        $this->assertEquals("792,00", $this->webDriver->findElement(WebDriver\WebDriverBy::id("totalPA"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
