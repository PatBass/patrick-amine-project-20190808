<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class CalculReductionTest extends WebTestCase {

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
     * Method testCalculReduction
     * @test
     */
    public function testCalculReduction() {
		
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/CalculReduction | 
        $this->webDriver->get("$url/calcul/CalculReduction");	
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Calculer un prix après réduction - Calcul du prix réduit - service-public.fr")
		);	
		
		
        // click | id=PrixOrigine | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("PrixOrigine"))->click();
		
        // type | id=PrixOrigine | 200
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("PrixOrigine"))->sendKeys("200");
		
		
        // click | id=TauxReduction | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("TauxReduction"))->click();
		
        // type | id=TauxReduction | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("TauxReduction"))->sendKeys("10");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Question_2e_Taux_1'))
		);		
		
        // click | id=Question_2e_Taux_1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Question_2e_Taux_1"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('TauxReduction2'))
		);			
		
		
        // click | id=TauxReduction2 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("TauxReduction2"))->click();
		
		
        // type | id=TauxReduction2 | 20
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("TauxReduction2"))->sendKeys("2");
		
		// Attendre jusqu'à l'affichage du bloc suivant  .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('PrixFinal'))
		);		
		
		
        // assertText | id=PrixFinal | 176,40
        $this->assertEquals("176,40", $this->webDriver->findElement(WebDriver\WebDriverBy::id("PrixFinal"))->getText());	
    }

    /**
     * Close the current window.
     */
    public function tearDown() {
        $this->webDriver->close();
    }

}
