<?php

namespace App\Tests\FunctionalTests\Front\LettreRetraite;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
use Symfony\Component\Dotenv\Dotenv;
use Facebook\WebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class LettreRetraiteTest extends WebTestCase
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
     * Method testLettreRetraite
     * @test
     */
    public function testLettreRetraite()
    {
		$url = $this->getEnvParamValue('URL_FUNCTIONAL_TESTS');
		
        // open | $url/calcul/Retraite/particuliers/tryIt | 
        $this->webDriver->get("$url/calcul/Retraite/particuliers/tryIt");
		
		// Attendre jusqu'à le chargement totale de la page // attendre au max 10s et rejouer chaque 1000ms .
		$this->webDriver->wait(10, 100)->until(
		  WebDriver\WebDriverExpectedCondition::titleIs("Notification de départ à la retraite du salarié - Modèle de lettre - service-public.fr")
		);	
		
        // click | id=boutt1 | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("boutt1"))->click();
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Prenom'))
		);			
        // type | id=Prenom | Prenom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Prenom"))->sendKeys("Prenom");
        // type | id=name | Nom
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("name"))->sendKeys("Nom");
        // type | id=AdresseExp | Adresse
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseExp"))->sendKeys("Adresse");
        // type | id=CodePostalExpediteur | 75001
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalExpediteur"))->sendKeys("75001");
        // type | id=VilleExp | Paris
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleExp"))->sendKeys("Paris");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('NomSocieteDest'))
		);			
        // click | id=NomSocieteDest | 
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("NomSocieteDest"))->click();
        // type | id=NomSocieteDest | Employeur
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("NomSocieteDest"))->sendKeys("Employeur");
        // type | id=AdresseDestinataire | adresseEmployeur
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("AdresseDestinataire"))->sendKeys("adresseEmployeur");
        // type | id=CodePostalDestinataire | 75002
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("CodePostalDestinataire"))->sendKeys("75002");
        // type | id=VilleDestinataire | Paris
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleDestinataire"))->sendKeys("Paris");
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('DateJour'))
		);					
        // type | id=DateJour | 01/07/2019
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("DateJour"))->clear();
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("DateJour"))->sendKeys("01/07/2019");
        // type | id=VilleLettre | Paris
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("VilleLettre"))->sendKeys("Paris");
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::id('Duree_Preavis'))
		);			
        // type | id=Date_depart | 31/07/2019
		$this->webDriver->findElement(WebDriver\WebDriverBy::id("Date_depart"))->clear();
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Date_depart"))->sendKeys("31/07/2019");
        // type | id=Duree_Preavis | 1
        $this->webDriver->findElement(WebDriver\WebDriverBy::id("Duree_Preavis"))->sendKeys("1");
		
		
		// Attendre jusqu'à l'affichage du bloc suivant .
		$this->webDriver->wait(10)->until(
		  WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(WebDriver\WebDriverBy::name("DeplacerFocus"))
		);				
        // assertElementPresent | name=DeplacerFocus | 
        $this->assertEquals(true, $this->webDriver->findElement(WebDriver\WebDriverBy::name("DeplacerFocus"))->isDisplayed() );	
		
        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='afficherboutonsuivant'])[1]/following::p[2] | Prenom NOM
        $this->assertEquals("Prenom NOM", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='afficherboutonsuivant'])[1]/following::p[2]"))->getText());

        // assertText | xpath=(.//*[normalize-space(text()) and normalize-space(.)='À Paris, le 1 juillet 2019'])[1]/following::p[4] | Mon départ, compte tenu du préavis de 1 mois à respecter, prendra effet à partir du 31 juillet 2019.
        $this->assertEquals("Mon départ, compte-tenu du préavis de 1 mois à respecter, prendra effet à partir du 31 juillet 2019.", $this->webDriver->findElement(WebDriver\WebDriverBy::xpath("(.//*[normalize-space(text()) and normalize-space(.)='À Paris, le 1 juillet 2019'])[1]/following::p[4]"))->getText());

    }

    /**
     * Close the current window.
     */
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
