<?php

namespace App\Tests\FunctionalTests\Front\All;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class LicenciementAbusifTest extends WebTestCase
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
     * Method testLicenciementAbusif
     * @test
     */
    public function testLicenciementAbusif()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/bareme-indemnites-prudhomales/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/bareme-indemnites-prudhomales/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Simulateur des indemnités en cas de licenciement abusif - Situation - service-public.fr")
		);	
		
        // click | id=inputNbrAnciennete | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("inputNbrAnciennete"))->click();
        // type | id=inputNbrAnciennete | 20
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("inputNbrAnciennete"))->sendKeys("20");

		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name('btnSubmitAnciennete'))
		);			
        // click | name=btnSubmitAnciennete | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::name("btnSubmitAnciennete"))->click();
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('indemniteMinimale-container'))
		);			
        // assertText | id=indemniteMinimale-container | Indemnité minimale : 3 mois de salaire brut
        $this->assertEquals("Indemnité minimale :\n3 mois de salaire brut", $this->webDriver->findElement(WebDriver\WebDriverBy::id("indemniteMinimale-container"))->getText());
        // assertText | id=indemniteMaximale-container | Indemnité maximale : 15,5 mois de salaire brut
        $this->assertEquals("Indemnité maximale :\n15,5 mois de salaire brut", $this->webDriver->findElement(WebDriver\WebDriverBy::id("indemniteMaximale-container"))->getText());
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }

    /**
     * @param WebDriver\Remote\RemoteWebElement $element
     *
     * @return WebDriver\WebDriverSelect
     * @throws WebDriver\Exception\UnexpectedTagNameException
     */
    private function getSelect(WebDriver\Remote\RemoteWebElement $element): WebDriver\WebDriverSelect
    {
        return new WebDriver\WebDriverSelect($element);
    }
}
