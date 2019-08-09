<?php

namespace App\Tests\FunctionalTests\Front\All\Headless;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class FraisImputablesLocataireTest extends WebTestCase
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
     * Method testFraisImputablesLocataire
     * @test
     */
    public function testFraisImputablesLocataire()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/frais-locataire/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/frais-locataire/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur des frais de mise en location imputables au locataire - Zonage - service-public.fr")
		);		
		
        // click | id=geoAPILocalitiescommune | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune"))->click();
		
		
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("//body"))
		);			
        // click | //body | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("//body"))->click();
		
		
        // type | id=geoAPILocalitiescommune | Lyon (69006)
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune"))->sendKeys("Lyon (69006)");
		
		
		
		
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-suggestions-suggestion-10"))
		);			
        // click | id=geoAPILocalitiescommune-suggestions-suggestion-10 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-suggestions-suggestion-10"))->click();
	
	
        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Simulateur des frais de mise en location imputables au locataire'])[2]/following::p[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Simulateur des frais de mise en location imputables au locataire'])[2]/following::p[1]"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-validate-button"))
		);			
        // click | id=geoAPILocalitiescommune-validate-button | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-validate-button"))->click();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-validate-button"))->click();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("geoAPILocalitiescommune-validate-button"))->click();

		// Attendre jusqu'à l'affichage du bloc suivant.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Attention'])[1]/following::p[1]"))
		);			
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Attention'])[1]/following::p[1] | Lyon est en zone tendue		
        $this->assertEquals("Lyon est en zone tendue", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Attention'])[1]/following::p[1]"))->getText());
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Attention'])[1]/following::p[2] | le tarif maximum imputable au locataire est de 10 euros TTC par m2 de surface habitable (*)
        $this->assertTrue((bool)preg_match('/^le tarif maximum imputable au locataire est de 10 euros TTC par m2 de surface habitable \([\s\S]*\)$/',$this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Attention'])[1]/following::p[2]"))->getText()));
    }


    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }

}
