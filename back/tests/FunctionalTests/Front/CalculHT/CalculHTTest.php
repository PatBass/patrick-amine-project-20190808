<?php

namespace App\Tests\FunctionalTests\Front\CalculHT;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class CalculHTTest extends WebTestCase {
    
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
     * Method testCalculHT
     * @test
     */
    public function testCalculHT()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/convertisseurPrixHTouTTC/professionnels-entreprises/tryIt | 
        $this->webDriver->get("$url/calcul/convertisseurPrixHTouTTC/professionnels-entreprises/tryIt");

		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Calculateur prix HT ou TTC - Conversion HT <=> TTC - service-public.fr")
		);		

        // click | id=prixTTC | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("prixTTC"))->click();
        // type | id=prixTTC | 2000
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("prixTTC"))->sendKeys("2000");
		
        // click | id=tauxTVA | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("tauxTVA"))->click();
        // select | id=tauxTVA_2 | label=10 %
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("tauxTVA_2"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('prixHT'))
		);			
        // assertText | id=prixHT | 1 818,18
        $this->assertEquals("1 818,18", $this->webDriver->findElement(WebDriver\WebDriverBy::id("prixHT"))->getText());
		
        // click | id=tauxTVA | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("tauxTVA"))->click();
        // select | id=tauxTVA_1 | label=20 %
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("tauxTVA_1"))->click();
	
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('prixHT'))
		);				
        // assertText | id=prixHT | 1 666,67
        $this->assertEquals("1 666,67", $this->webDriver->findElement(WebDriver\WebDriverBy::id("prixHT"))->getText());
		
        // click | id=ui-tab-panel-2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ui-tab-panel-2"))->click();		

        // click | id=montantHT2HtVersTtc | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("montantHT2HtVersTtc"))->click();
		
        // click | id=ui-tab-panel-2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ui-tab-panel-2"))->click();
        // type | id=montantHT2HtVersTtc | 2000
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("montantHT2HtVersTtc"))->sendKeys("2000");
        // click | id=tauxTVA2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("tauxTVA2"))->click();
		
		
        // select | id=tauxTVA2_1 | label=20 %
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("tauxTVA2_1"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('montantTTC2HtVersTtc'))
		);				
        // assertText | id=montantTTC2HtVersTtc | 2 400,00
        $this->assertEquals("2 400,00", $this->webDriver->findElement(WebDriver\WebDriverBy::id("montantTTC2HtVersTtc"))->getText());
    }
	
    /**
     * Close the current window.
     */
    public function tearDown() {
        $this->webDriver->close();
    }	

}
