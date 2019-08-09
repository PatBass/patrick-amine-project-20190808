<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class UsufruitNueProprieteTest extends WebTestCase
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
     * Method testUsufruitNuePropriete
     * @test
     */
    public function testUsufruitNuePropriete()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/bareme-fiscal-usufruit/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/bareme-fiscal-usufruit/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur : barème fiscal de l'usufruit et de la nue-propriété - saisie montant et âge - service-public.fr")
		);	
		
        // click | id=ValeurBien | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ValeurBien"))->click();
        // type | id=ValeurBien | 1000000
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ValeurBien"))->sendKeys("1000000");
        // type | id=ageusufuitier | 80
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("ageusufuitier"))->sendKeys("80");
        // click | name=calculer | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::name("calculer"))->click();
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('valeurUsufruit-container'))
		);				
        // assertText | id=valeurUsufruit-container | Valeur fiscale de l'usufruit300 000,000 €
        $this->assertEquals("Valeur fiscale de l'usufruit\n300 000,00 €", $this->webDriver->findElement(WebDriver\WebDriverBy::id("valeurUsufruit-container"))->getText());
        // assertText | id=pourcentnuepropriete-container | Nue-propriété en % de la valeur des biens70,00%
        $this->assertEquals("Nue-propriété en % de la valeur des biens\n70,00%", $this->webDriver->findElement(WebDriver\WebDriverBy::id("pourcentnuepropriete-container"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
