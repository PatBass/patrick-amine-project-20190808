<?php

namespace App\Tests\FunctionalTests\Front\ForfaitBOAMP;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class ForfaitBOAMPTest extends WebTestCase
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
     * Method testForfaitBOAMP
     * @test
     */
    public function testForfaitBOAMP()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/forfaits/boamp-acheteur/tryIt | 
        $this->webDriver->get("$url/calcul/forfaits/boamp-acheteur/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur d'aide au choix d'un forfait BOAMP - Choix forfaits (dynamique) - Boamp.fr")
		);			
		
        // click | id=nbAvisInitiauxMapa | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisInitiauxMapa"))->click();
        // type | id=nbAvisInitiauxMapa | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisInitiauxMapa"))->sendKeys("10");
        // type | id=nbAvisInitiauxNational | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisInitiauxNational"))->sendKeys("10");
        // type | id=nbAvisInitiauxEuropeen | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisInitiauxEuropeen"))->sendKeys("10");
        // type | id=nbAvisResultatsMapa | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisResultatsMapa"))->sendKeys("10");
        // type | id=nbAvisResultatsNational | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisResultatsNational"))->sendKeys("10");
        // type | id=nbAvisResultatsEuropeen | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisResultatsEuropeen"))->sendKeys("10");
        // type | id=nbAvisIntentionConclureMapa | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisIntentionConclureMapa"))->sendKeys("10");
        // type | id=nbAvisIntentionConclureNational | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisIntentionConclureNational"))->sendKeys("10");
        // type | id=nbAvisIntentionConclureEuropeen | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisIntentionConclureEuropeen"))->sendKeys("10");
        // type | id=nbAvisRectificatifsMapa | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisRectificatifsMapa"))->sendKeys("10");
        // type | id=nbAvisRectificatifsNational | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisRectificatifsNational"))->sendKeys("10");
        // type | id=nbAvisRectificatifsEuropeen | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisRectificatifsEuropeen"))->sendKeys("10");
        // type | id=nbAvisAnnulationMapa | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisAnnulationMapa"))->sendKeys("10");
        // type | id=nbAvisAnnulationNational | 10
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("nbAvisAnnulationNational"))->sendKeys("10");
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='MAPA < 90 K€ / Marchés Nationaux (150 UP) :'])[1]/following::p[4] | 1 forfait(s) de 120 UP / 12 mois + 14 UP offerte(s)
        $this->assertEquals("1 forfait(s) de 120 UP / 12 mois + 14 UP offerte(s)", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='MAPA < 90 K€ / Marchés Nationaux (150 UP) :'])[1]/following::p[4]"))->getText());
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Marchés européens (160 UP) :'])[1]/following::p[4] | 1 forfait(s) de 120 UP / 12 mois + 14 UP offerte(s)
        $this->assertEquals("1 forfait(s) de 120 UP / 12 mois + 14 UP offerte(s)", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Marchés européens (160 UP) :'])[1]/following::p[4]"))->getText());
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Marchés européens (160 UP) :'])[1]/following::strong[3] | 25 200,00
        $this->assertEquals("25 200,00", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Marchés européens (160 UP) :'])[1]/following::strong[3]"))->getText());
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Marchés européens (160 UP) :'])[1]/following::strong[4] | 9,68
        $this->assertEquals("9,68", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Marchés européens (160 UP) :'])[1]/following::strong[4]"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
