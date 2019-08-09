<?php

namespace App\Tests\FunctionalTests\Front\CarteGrise;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class AffichageConditionTest extends WebTestCase {

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
     * Method testAffichageCondition
     * @test
     */
    public function testAffichageCondition() {
		
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/cout-certificat-immatriculation | 
        $this->webDriver->get("$url/calcul/cout-certificat-immatriculation");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur du coût du certificat d'immatriculation - Données - service-public.fr")
		);			
		
        // click | id=combobox-demarche | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("combobox-demarche"))->click();		
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='*'])[4]/following::a[1] | 
        //$this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='*'])[4]/following::a[1]"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant  .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('itemdemarche-2'))
		);			
        // click | id=itemdemarche-2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("itemdemarche-2"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant  .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('franceOuImport-container'))
		);			
        // Tester si l'élement est visible ou nn
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::id("franceOuImport-container"))->isDisplayed() );		
			
			
        // click | id=combobox-demarche | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("combobox-demarche"))->click();	
		
		// Attendre jusqu'à l'affichage du bloc suivant  .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('itemdemarche-3'))
		);			
        // click | id=itemdemarche-3 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("itemdemarche-3"))->click();
		

		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('typeVehicule-container'))
		);			
        // Tester si l'élement est visible ou nn
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::id("typeVehicule-container"))->isDisplayed() );					
    }

    /**
     * Close the current window.
     */
    public function tearDown() {
        $this->webDriver->close();
    }

}
