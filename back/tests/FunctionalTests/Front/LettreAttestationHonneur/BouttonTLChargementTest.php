<?php

namespace App\Tests\FunctionalTests\Front\LettreAttestationHonneur;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class BouttonTLChargementTest extends WebTestCase
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
     * Method testBouttonTLChargement
     * @test
     */
    public function testBouttonTLChargement()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/AttestationHonneur | 
        $this->webDriver->get("$url/calcul/AttestationHonneur");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Attestation sur l'honneur - Modèle de lettre - service-public.fr")
		);	
		
        // click | id=boutt1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("boutt1"))->click();
		
        // click | id=Prenom | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->click();		

        // type | id=Prenom | Prénom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->sendKeys("Prénom");
		
        // click | id=name | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("name"))->click();			

        // type | id=name | Nom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("name"))->sendKeys("Nom");

        // click | xpath=(.//*[normalize-space(text()) and normalize-space(.)='Madame'])[1]/following::label[1] | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='Madame'])[1]/following::label[1]"))->click();

        // click | id=AdresseExp | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseExp"))->click();

        // type | id=AdresseExp | Adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseExp"))->sendKeys("Adresse");

        // click | id=CodePostalExpediteur | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalExpediteur"))->click();

        // type | id=CodePostalExpediteur | 12345
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalExpediteur"))->sendKeys("12345");

        // click | id=VilleExp | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleExp"))->click();

        // type | id=VilleExp | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleExp"))->sendKeys("Commune");	
		
		// Attendre jusqu'à l'affichage du bloc fait attention // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('FaitsAttestation'))
		);		
		
        // click | id=FaitsAttestation | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("FaitsAttestation"))->click();

        // type | id=FaitsAttestation | J'atteste
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("FaitsAttestation"))->sendKeys("J'atteste\nJ'atteste\nJ'atteste");

        $this->webDriver->findElement(WebDriver\WebDriverBy::id("laDateDuJour"))->clear();
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("laDateDuJour"))->sendKeys("31/07/2019");	
		

		// Attendre jusqu'à l'affichage du bloc // attendre au max 10s.
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Commune'))
		);			

        // click | id=Commune | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Commune"))->click();

        // type | id=Commune | Commune
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Commune"))->sendKeys("Commune");

		// Attendre jusqu'à l'affichage du bloc  // attendre au max 10s .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name("DeplacerFocus"))
		);				

        // Tester si l'élement bouton télécharger est visible ou nn
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::name("DeplacerFocus"))->isDisplayed() );
    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
